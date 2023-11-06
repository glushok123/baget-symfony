<?php

namespace App\Service;

use App\Dto\Product\Brand\BrandDto;
use App\Dto\Product\Category\CategoryDto;
use App\Dto\Product\Filter\FilterDto;
use App\Dto\Product\Image\ImageDto;
use App\Dto\Product\Model\ModelDto;
use App\Dto\Product\Pagination\PaginationDto;
use App\Dto\Product\ProductCollectionDto;
use App\Dto\Product\ProductDto;
use App\Dto\Product\Request\RequestGetCollectionDto;
use App\Dto\Product\Request\RequestSearchDto;
use App\Dto\Product\Type\TypeDto;
use App\Entity\Product\Product;
use App\Entity\Product\ProductCategory;
use App\Entity\Product\ProductType;
use App\Repository\ProductBrandRepository;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductModelRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductTypeRepository;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Meilisearch\Bundle\SearchService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductService
{
    private $serializer;

    public function __construct(
        private readonly ProductRepository              $repository,
        private readonly ProductCategoryRepository      $categoryRepository,
        private readonly ProductTypeRepository          $typeRepository,
        private readonly ProductBrandRepository         $brandRepository,
        private readonly ProductModelRepository         $modelRepository,
        private readonly TranslatorInterface            $translator,
        private readonly EntityManagerInterface         $entityManager,
        private readonly SearchService                  $searchService,
        private readonly ImageService                   $imageService
    )
    {
        $metadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new PropertyNormalizer($metadataFactory, null);

        $this->serializer = new Serializer([$normalizer, new DateTimeNormalizer(), new ObjectNormalizer()]);
    }

    public function getCollection(PaginatorInterface $paginator, RequestGetCollectionDto $dto, array $productsDto = [], int $activeCategory = null, int $activeType = null, int $minPrice = 0, int $maxPrice = 0): ProductCollectionDto
    {
        $condition = ['deleted' => 0, 'active' => 1];

        if (!empty($dto->category)){
            $condition['category'] = $dto->category;
            $activeCategory = $this->categoryRepository->findOneBy(['id' => $condition['category']]);
        }

        if (!empty($dto->type)){
            $condition['type'] = $dto->type;
            $activeType = $this->typeRepository->findOneBy(['id' => $condition['type']]);
        }

        if (!empty($dto->brand)){
            $condition['brand'] = $dto->brand;
        }

        if (!empty($dto->model)){
            $condition['model'] = $dto->model;
        }

        if (!empty($dto->min_price)){
            $minPrice = $dto->min_price;
        }

        if (!empty($dto->max_price)){
            $maxPrice = $dto->max_price;
        }

        $products = $this->repository->findBy($condition, ['sortingNumber' => 'DESC']);

        if (empty($products)) {
            throw new Exception($this->translator->trans('product_could_not_be_found'));
        }

        $collectionProduct = [];
        $collectionPrice = [];

        foreach ($products as $product){
            if (!empty($minPrice)){
                if (!($product->getPrice()->getRubValue() >= $minPrice)){
                    continue;
                }
            }

            if (!empty($maxPrice)){
                if (!($product->getPrice()->getRubValue() <= $maxPrice)){
                    continue;
                }
            }

            if (!empty($product->getPrice()->getRubValue())){
                $collectionPrice[] = $product->getPrice()->getRubValue();
            }

            $collectionProduct[] = $product;
        }

        $pagination = $paginator->paginate($collectionProduct, $dto->page ?? 1, $dto->limit ?? 16);

        foreach ($pagination->getItems() as $product) {
            $image = empty($product->getImage()->getName()) ? new ImageDto() : $this->imageService->getResizeImagePath($product->getImage()->getName());
            $productDto = $this->serialize($product, ProductDto::class, ['image'], [], ['image' =>  $image]);
            $productsDto['products'][] = $productDto->toArray(['search']);
        }

        $productsDto['pagination'] = new PaginationDto(
            currentPageNumber : $pagination->getCurrentPageNumber(),
            numItemsPerPage : $pagination->getItemNumberPerPage(),
            totalCount : $pagination->getTotalItemCount()
        );

        $productsDto['filter'] = new FilterDto(
            category: $this->getProductCategoryFilter(),
            brand: $this->getProductBrandFilter($activeCategory, $activeType),
            model: $this->getProductModelFilter($activeCategory, $activeType),
            minPrice: empty($collectionPrice) ? null : min($collectionPrice),
            maxPrice: empty($collectionPrice) ? null : max($collectionPrice),
        );

        return new ProductCollectionDto(
            products: $productsDto['products'],
            pagination: $productsDto['pagination'],
            filter: $productsDto['filter']
        );
    }

    public function getProduct(ProductDto $dto): ProductDto
    {
        if (empty($dto->id) && empty($this->repository->findOneBy(['id' => $dto->id]))){
            throw new Exception($this->translator->trans('product_could_not_be_found'));
        }

        $product = $this->repository->findOneBy(['id' => $dto->id]);
        $image = empty($product->getImage()->getName()) ? new ImageDto() : $this->imageService->getResizeImagePath($product->getImage()->getName());

        return $this->serialize($product, ProductDto::class, ['image'], [], ['image' =>  $image]);
    }

    public function getProductFilter(array $category = [], array $brand = [],array $model = []): FilterDto
    {
        foreach ($this->categoryRepository->findBy(['deleted' => 0, 'active' => 1], ['sortingNumber' => 'DESC']) as $categoryItem) {
            $category[] = $this->serialize($categoryItem, CategoryDto::class, ['products', 'updatedAt', 'createdAt', 'deleted', 'active', 'category', 'externalId'], ['id', 'name', 'productTypes'])->toArray(['filter']);
        }

        foreach ($this->brandRepository->findBy(['deleted' => 0, 'active' => 1]) as $brandItem) {
            $brand[] = $this->serialize($brandItem, BrandDto::class, [], [])->toArray(['filter']);
        }

        foreach ($this->modelRepository->findBy(['deleted' => 0, 'active' => 1]) as $modelItem) {
            $model[] = $this->serialize($modelItem, ModelDto::class, [], [])->toArray(['filter']);
        }

        return new FilterDto(
            category: $category,
            brand: $brand,
            model: $model
        );
    }

    public function getProductCategoryFilter(array $category = []): array
    {
        foreach ($this->categoryRepository->findBy(['deleted' => 0, 'active' => 1], ['sortingNumber' => 'DESC']) as $categoryItem) {
            $category[] = $this->serialize($categoryItem, CategoryDto::class, ['products', 'updatedAt', 'createdAt', 'deleted', 'active', 'category', 'externalId'], ['id', 'name', 'productTypes'])->toArray(['filter']);
        }

        return $category;
    }

    public function getProductBrandFilter(?ProductCategory $category = null, ?ProductType $type = null, array $brand = []): array
    {
        $condition = ['deleted' => 0, 'active' => 1];

        if (!empty($category)){
            $condition['category'] = $category;
        }

        if (!empty($type)){
            $condition['type'] = $type;
        }

        $products = $this->repository->findBy($condition);

        foreach ($products as $product) {
            if (!empty($product->getBrand())){
                $brandDto = $this->serialize($product->getBrand(), BrandDto::class, ['products', 'updatedAt', 'createdAt', 'deleted', 'active', 'category', 'externalId'], ['id', 'name'])->toArray(['filter']);
                if (!in_array($brandDto, $brand)) {
                    $brand[] = $brandDto;
                }
            }
        }

        return $brand;
    }

    public function getProductModelFilter(?ProductCategory $category = null, ?ProductType $type = null, array $model = []): array
    {
        $condition = ['deleted' => 0, 'active' => 1];

        if (!empty($category)){
            $condition['category'] = $category;
        }

        if (!empty($type)){
            $condition['type'] = $type;
        }

        $products = $this->repository->findBy($condition);

        foreach ($products as $product) {
            if (!empty($product->getModel())){
                $modelDto = $this->serialize($product->getModel(), ModelDto::class, ['products', 'updatedAt', 'createdAt', 'deleted', 'active', 'category', 'externalId'], ['id', 'name'])->toArray(['filter']);
                if (!in_array($modelDto, $model)) {
                    $model[] = $modelDto;
                }
            }
        }

        return $model;
    }

    public function getProductSearch(PaginatorInterface $paginator, RequestSearchDto $dto, array $productsDto = [], string $filter = '', array $productsTypeDto = [], array $productsBrandDto = []): ProductCollectionDto
    {
        if (!empty(array_filter($dto->category))){
            $filter .= 'category.id IN [' . implode(',', $dto->category) . ']';
        }

        if (!empty(array_filter($dto->type))){
            if (!empty($filter)) {
                $filter .= ' AND ';
            }
            $filter .= 'type.id IN [' . implode(',', $dto->type) . ']';
        }

        if (!empty(array_filter($dto->brand))){
            if (!empty($filter)) {
                $filter .= ' AND ';
            }
            $filter .= 'brand.id IN [' . implode(',', $dto->brand) . ']';
        }

        if (!empty($dto->min_price)){
            if (!empty($filter)) {
                $filter .= ' AND ';
            }
            $filter .= 'price.rubValue >= ' . $dto->min_price;
        }

        if (!empty($dto->max_price)){
            if (!empty($filter)) {
                $filter .= ' AND ';
            }
            $filter .= 'price.rubValue <= ' . $dto->max_price;
        }

        $products = $this->searchService->search($this->entityManager, Product::class, $dto->query, ['filter' => $filter]);

        if (empty($products)) {
            throw new Exception($this->translator->trans('product_could_not_be_found'));
        }

        foreach ($products as $product) {
            if(!empty($product->getType())){
                $productTypeDto = $this->serialize($product->getType(), TypeDto::class, ['category', 'products', 'createdAt', 'externalId']);
                $productsTypeDto[] = $productTypeDto->toArray(['search']);
            }

            if(!empty($product->getBrand())){
                $productBrandDto = $this->serialize($product->getType(), BrandDto::class, ['category', 'products', 'createdAt', 'externalId']);
                $productsBrandDto[] = $productBrandDto->toArray(['search']);
            }
        }

        $pagination = $paginator->paginate($products, $dto->page ?? 1, $dto->limit ?? 16);

        foreach ($pagination->getItems() as $product) {
            $image = empty($product->getImage()->getName()) ? new ImageDto() : $this->imageService->getResizeImagePath($product->getImage()->getName());
            $productDto = $this->serialize($product, ProductDto::class, ['parent', 'children', 'externalId', 'productTypes', 'image'], [], ['image' =>  $image]);
            $productsDto['products'][] = $productDto->toArray(['search']);
        }

        $productsDto['pagination'] = new PaginationDto(
            currentPageNumber : $pagination->getCurrentPageNumber(),
            numItemsPerPage : $pagination->getItemNumberPerPage(),
            totalCount : $pagination->getTotalItemCount()
        );

        $collectionPrice = [];

        foreach ($products as $product) {
            if (!empty($product->getPrice()->getRubValue())){
                $collectionPrice[] = $product->getPrice()->getRubValue();
            }
        }

        $productsDto['filter'] = new FilterDto(
            type: $productsTypeDto,
            brand: $productsBrandDto,
            minPrice: empty($collectionPrice) ? null : min($collectionPrice),
            maxPrice: empty($collectionPrice) ? null : max($collectionPrice),
        );

        return new ProductCollectionDto(
            products: $productsDto['products'],
            pagination: $productsDto['pagination'],
            filter: $productsDto['filter']
        );
    }

    public function serialize($entity, $dto, array $ignoredAttribute = [], array $onlyAttribute = [], array $addAttribute = [])
    {
        $conditionOnlyAttribute = empty($onlyAttribute) ? [null] : [AbstractNormalizer::ATTRIBUTES => $onlyAttribute];
        $conditionIgnoredAttribute = empty($ignoredAttribute) ? [null] : [AbstractNormalizer::IGNORED_ATTRIBUTES => $ignoredAttribute];

        $condition = array_merge([
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
            AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true
        ], $conditionOnlyAttribute, $conditionIgnoredAttribute);
        $normalize = array_merge($this->serializer->normalize($entity, null, $condition), $addAttribute);

        return $this->serializer->denormalize(
            $normalize, $dto
        );
    }
}
