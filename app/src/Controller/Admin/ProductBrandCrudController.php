<?php

namespace App\Controller\Admin;

use App\Entity\Product\ProductBrand;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;

class ProductBrandCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductBrand::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Бренды')
            ->setPageTitle('detail', fn (ProductBrand $productBrand) => (string) $productBrand->getName())
            ->setPageTitle('edit', fn (ProductBrand $productBrand) => (string) $productBrand->getName())
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
            TextField::new('name', 'Название'),

            BooleanField::new('deleted', 'Удален')->setColumns('col-sm-3 col-lg-3 col-xxl-3'),
            BooleanField::new('active', 'Активен')->setColumns('col-sm-3 col-lg-3 col-xxl-3'),
        ];
    }
}
