<?php

namespace App\Controller\Admin;

use App\Entity\Product\ProductCategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductCategory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Категории')
            ->setPageTitle('detail', fn (ProductCategory $productCategory) => (string) $productCategory->getName())
            ->setPageTitle('edit', fn (ProductCategory $productCategory) => (string) $productCategory->getName())
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            IdField::new('externalId', 'Внешний ID')->hideOnForm(),
            IdField::new('sortingNumber', 'Сортировка'),
            TextField::new('name', 'Название'),

            ImageField::new('Image.name', 'Картинка')
                ->setUploadDir('public/upload/images/product/')
                ->setUploadedFileNamePattern('[timestamp]-[contenthash].[extension]')
                ->hideOnIndex(),

            BooleanField::new('deleted', 'Удален')->setColumns('col-sm-3 col-lg-3 col-xxl-3'),
            BooleanField::new('active', 'Активен')->setColumns('col-sm-3 col-lg-3 col-xxl-3'),
        ];
    }

}
