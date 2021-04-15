<?php

namespace App\Controller\Admin;

use App\Entity\IsInCommunity;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class IsInCommunityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return IsInCommunity::class;
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
