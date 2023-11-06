<?php

namespace App\Controller\Admin;

use App\Entity\Product\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Товары')
            ->setPageTitle('detail', fn (Product $product) => (string) $product->getName())
            ->setPageTitle('edit', fn (Product $product) => (string) $product->getName())
            ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('id')
            ->add('article')
            ->add('count')
            ->add('countTransit')
            ->add('minCount')
            ->add(BooleanFilter::new('deleted'))
            ->add(BooleanFilter::new('active'))
            ->add(BooleanFilter::new('colorBlack'))
            ->add(BooleanFilter::new('colorMagenta'))
            ->add(BooleanFilter::new('colorYellow'))
            ->add(BooleanFilter::new('colorCyan'))
            ->add(BooleanFilter::new('colorWhite'))
            ->add(BooleanFilter::new('colorTransparent'))
            ->add(BooleanFilter::new('formatA0'))
            ->add(BooleanFilter::new('formatA1'))
            ->add(BooleanFilter::new('formatA2'))
            ->add(BooleanFilter::new('formatA3'))
            ->add(BooleanFilter::new('formatA4'))
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Название'),
            TextField::new('article', 'Артикул из Паука'),
            IdField::new('sortingNumber', 'Сортировка'),
            ImageField::new('Image.name', 'Картинка')
                ->setUploadDir('public/upload/images/product/')
                ->setUploadedFileNamePattern('[timestamp]-[contenthash].[extension]')
                ->hideOnIndex(),

            AssociationField::new('category', 'Категория')
                ->setFormType(EntityType::class)
                ->setFormTypeOption('choice_label', function($choice) {
                    return $choice->getName();
                })
                ->onlyWhenUpdating()
                ->setColumns(6),

            AssociationField::new('type', 'Тип')
                ->setFormType(EntityType::class)
                ->setFormTypeOption('choice_label', function($choice) {
                    return $choice->getName();
                })
                ->onlyWhenUpdating()
                ->setColumns(6),

            AssociationField::new('brand', 'Бренд')
                ->setFormType(EntityType::class)
                ->setFormTypeOption('choice_label', function($choice) {
                    return $choice->getName();
                })
                ->onlyWhenUpdating()
                ->setColumns(6),

            AssociationField::new('model', 'Модель')
                ->setFormType(EntityType::class)
                ->setFormTypeOption('choice_label', function($choice) {
                    return $choice->getName();
                })
                ->onlyWhenUpdating()
                ->setColumns(6),

            BooleanField::new('deleted', 'Удален')->setColumns('col-sm-3 col-lg-3 col-xxl-3'),
            BooleanField::new('active', 'Активен')->setColumns('col-sm-3 col-lg-3 col-xxl-3'),

            FormField::addPanel('Количество'),
            IntegerField::new('count', 'Количество доступных к заказу')->setColumns(3)->hideOnIndex(),
            IntegerField::new('countTransit', 'Количество находящихся в транзите')->setColumns(3)->hideOnIndex(),
            IntegerField::new('minCount', 'Минимальное количество')->setColumns(3)->hideOnIndex(),

            FormField::addPanel('Цены'),
            NumberField::new('Price.rubValue', 'Цена за штуку (доступных) RUB ')->setColumns(3)->hideOnIndex(),
            NumberField::new('Price.usdValue', 'Цена за штуку (доступных) USD')->setColumns(3)->hideOnIndex(),
            NumberField::new('Price.rubTransitValue', 'Цена за штуку (транзитных) RUB')->setColumns(3)->hideOnIndex(),
            NumberField::new('Price.usdTransitValue', 'Цена за штуку (транзитных) USD')->setColumns(3)->hideOnIndex(),

            FormField::addPanel('Цвета'),
            BooleanField::new('colorBlack', 'Черный')->setColumns(2)->hideOnIndex(),
            BooleanField::new('colorMagenta', 'Пурпурный')->setColumns(2)->hideOnIndex(),
            BooleanField::new('colorYellow', 'Желтый')->setColumns(2)->hideOnIndex(),
            BooleanField::new('colorCyan', 'Голубой')->setColumns(2)->hideOnIndex(),
            BooleanField::new('colorWhite', 'Белый')->setColumns(2)->hideOnIndex(),
            BooleanField::new('colorTransparent', 'Прозрачный')->setColumns(2)->hideOnIndex(),

            FormField::addPanel('Форматы'),
            BooleanField::new('formatA0', 'А0')->setColumns(1)->hideOnIndex(),
            BooleanField::new('formatA1', 'А1')->setColumns(1)->hideOnIndex(),
            BooleanField::new('formatA2', 'А2')->setColumns(1)->hideOnIndex(),
            BooleanField::new('formatA3', 'А3')->setColumns(1)->hideOnIndex(),
            BooleanField::new('formatA4', 'А4')->setColumns(1)->hideOnIndex(),
        ];
    }
}
