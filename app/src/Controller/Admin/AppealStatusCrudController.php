<?php

namespace App\Controller\Admin;

use App\Entity\Appeal\AppealStatus;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AppealStatusCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AppealStatus::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Статусы Обращений/претензий')
            ->setPageTitle('detail', fn (AppealStatus $status) => (string) $status->getName())
            ->setPageTitle('edit', fn (AppealStatus $status) => (string) $status->getName())
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
