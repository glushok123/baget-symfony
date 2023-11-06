<?php

namespace App\Controller\Admin;

use App\Entity\Appeal\AppealCategory;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class AppealCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AppealCategory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Категории Обращений/претензий')
            ->setPageTitle('detail', fn (AppealCategory $category) => (string) $category->getName())
            ->setPageTitle('edit', fn (AppealCategory $category) => (string) $category->getName())
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Название'),
        ];
    }
}
