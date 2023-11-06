<?php

namespace App\Controller\Admin;

use App\Entity\Appeal\AppealMessage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use DateTimeImmutable;

class AppealMessageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AppealMessage::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextareaField::new('message', 'Сообщение')
                ->onlyOnForms()
                ->setColumns(10),
                //->setFormTypeOption('disabled','disabled'),

            //DateTimeField::new('createdAt', 'Дата')
                //->setColumns(10),
                //->setFormTypeOption('disabled','disabled'),
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        $appealMessage = new AppealMessage();
        $appealMessage->setCreatedAt(new DateTimeImmutable());
        $appealMessage->setSender($this->getUser());

        return $appealMessage;
    }
}
