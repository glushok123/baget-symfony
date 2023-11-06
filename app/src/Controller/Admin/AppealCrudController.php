<?php

namespace App\Controller\Admin;

use App\Entity\Appeal\Appeal;
use App\Entity\Appeal\AppealMessage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use DateTimeImmutable;

class AppealCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Appeal::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'Номер')
                ->setFormTypeOption('disabled','disabled'),

            TextField::new('name', 'ФИО')
                ->setFormTypeOption('disabled','disabled'),

            AssociationField::new('user', 'Пользователь')
                ->autocomplete()
                ->setFormTypeOption('disabled','disabled'),

            AssociationField::new('category', 'Категория')
                ->autocomplete(),

            AssociationField::new('status', 'Статус')
                ->autocomplete(),

            AssociationField::new('appealMessages', 'Сообщения')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ])
                ->onlyOnIndex(),

             CollectionField::new('appealMessages', 'Сообщения')
                 ->renderExpanded()
                 ->useEntryCrudForm(AppealMessageCrudController::class)
                 ->setEntryIsComplex()
                 //->onlyWhenCreating()
                 //->setDisabled()
                 //->allowDelete(false)
                 ->onlyWhenUpdating()
                 ->setColumns(12),

            DateTimeField::new('createdAt', 'Дата создания')
                ->setFormTypeOption('disabled','disabled'),
        ];
    }
}
