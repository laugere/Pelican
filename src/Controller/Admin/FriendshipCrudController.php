<?php

namespace App\Controller\Admin;

use App\Entity\Friendship;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FriendshipCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Friendship::class;
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
