<?php

namespace App\Controller\Admin;

use App\Entity\RoleGroup;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class RoleGroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RoleGroup::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            AssociationField::new('roles')
                ->setFormType(EntityType::class)
                ->setFormTypeOption('choice_label', function($choice) {
                    return $choice->getNameRu() . ' (' . $choice->getName() . ')';
                })
                ->setFormTypeOption('by_reference', false)
                ->setFormTypeOption('expanded', true)
                ->onlyWhenUpdating()
        ];
    }
}
