<?php

namespace App\Controller\Admin;

use App\Entity\Bid;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BidCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Bid::class;
    }

    public function configureActions(Actions $actions): Actions {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }

    public function configureCrud(Crud $crud): Crud {
        return $crud
            ->setEntityLabelInSingular('Gebot')
            ->setEntityLabelInPlural('Gebote');
    }

    public function configureFilters(Filters $filters): Filters {
        return $filters
            ->add('auction')
            ->add('user');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('auction')
                ->setLabel('label.auction')
            ,
            AssociationField::new('user')->setLabel('label.user'),
            MoneyField::new('amount')->setCurrency('EUR')->setLabel('label.amount'),
            DateTimeField::new('createdAt')->setLabel('label.created_at'),
        ];
    }

}
