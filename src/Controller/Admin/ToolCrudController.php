<?php

namespace App\Controller\Admin;

use App\Entity\Tool;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ToolCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tool::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return
        [
            TextField::new('name'),
            TextEditorField::new('description'),
            ImageField::new('main_pic')
            ->setBasePath('uploads/')
            ->setUploadDir('public/uploads/')
            ->setRequired(false),
            ImageField::new('pic2')
            ->setBasePath('uploads/')
            ->setUploadDir('public/uploads/')
            ->setRequired(false),
            ImageField::new('pic3')
            ->setBasePath('uploads/')
            ->setUploadDir('public/uploads/')
            ->setRequired(false),
            AssociationField::new('category'),
        ];
    }
}
