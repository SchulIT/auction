<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureActions(Actions $actions): Actions {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureCrud(Crud $crud): Crud {
        return $crud
            ->setEntityLabelInSingular('Benutzer')
            ->setEntityLabelInPlural('Benutzer');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('username')->setDisabled(),
            TextField::new('firstname')->setLabel('label.firstname'),
            TextField::new('lastname')->setLabel('label.lastname'),
            EmailField::new('email')->setLabel('label.email'),
            TextField::new('grade')->setLabel('label.grade.label')->setHelp('label.grade.help')
        ];
    }

}
