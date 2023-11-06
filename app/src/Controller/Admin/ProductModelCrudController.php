<?php

namespace App\Controller\Admin;

use App\Entity\Product\ProductModel;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductModelCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductModel::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
