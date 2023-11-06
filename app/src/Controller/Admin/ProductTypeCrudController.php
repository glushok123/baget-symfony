<?php

namespace App\Controller\Admin;

use App\Entity\Product\ProductType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;

class ProductTypeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductType::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Типы')
            ->setPageTitle('detail', fn (ProductType $productType) => (string) $productType->getName())
            ->setPageTitle('edit', fn (ProductType $productType) => (string) $productType->getName())
            ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('id')
            ->add('externalId')
            ->add(BooleanFilter::new('deleted'))
            ->add(BooleanFilter::new('active'))
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            IdField::new('externalId', 'Внешний ID')->hideOnForm(),
            IdField::new('sortingNumber', 'Сортировка'),
            TextField::new('name', 'Название'),

            BooleanField::new('deleted', 'Удален')->setColumns('col-sm-3 col-lg-3 col-xxl-3'),
            BooleanField::new('active', 'Активен')->setColumns('col-sm-3 col-lg-3 col-xxl-3'),
        ];
    }
}
