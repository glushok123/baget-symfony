<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Field\PhoneField;
use App\Entity\User\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Пользователи')
            ->setPageTitle('detail', fn (User $user) => (string) $user->getName())
            ->setPageTitle('edit', fn (User $user) => (string) $user->getName())
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Имя')->setColumns(4),
            TextField::new('middleName', 'Отчество')->setColumns(4),
            TextField::new('surname', 'Фамилия')->setColumns(4),
            TextField::new('email', 'email'),


            BooleanField::new('sex', 'Пол')->hideOnIndex(),
            DateTimeField::new('birthday', 'День рождения')->hideOnIndex(),

            TextField::new('phone', 'Телефон')
                ->setFormType(PhoneNumberType::class)->hideOnIndex(),

            AssociationField::new('roleGroups', 'Группы')
                ->setFormType(EntityType::class)
                ->setFormTypeOption('choice_label', function($choice) {
                    return $choice->getName();
                })
                ->setFormTypeOption('by_reference', false),

            AssociationField::new('organizations', 'Организации')
                ->setFormType(EntityType::class)
                ->setFormTypeOption('choice_label', function($choice) {
                    return $choice->getName();
                })
                ->setFormTypeOption('by_reference', false),

            BooleanField::new('confirmEmail', 'Подтв. email'),
            BooleanField::new('deleted', 'Удален'),
        ];
    }
}
