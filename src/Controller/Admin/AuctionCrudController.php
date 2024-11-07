<?php

namespace App\Controller\Admin;

use App\Entity\Auction;
use App\Entity\Bid;
use App\Form\BidType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Image;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AuctionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Auction::class;
    }

    public function configureActions(Actions $actions): Actions {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureCrud(Crud $crud): Crud {
        return $crud
            ->setEntityLabelInSingular('Auktion')
            ->setEntityLabelInPlural('Auktionen');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title')
                ->setLabel('label.title'),
            CodeEditorField::new('description')
                ->hideOnIndex()
                ->setLanguage('markdown')
                ->setLabel('label.description'),

            ImageField::new('imageFileName')
                ->setLabel('label.image')
                ->setBasePath('auctions/')
                ->setUploadDir('public/auctions/')
                ->setFileConstraints(new Image(maxSize: '1M'))
                ->setUploadedFileNamePattern('[randomhash].[extension]'),


            DateTimeField::new('start')
                ->setLabel('label.start'),
            DateTimeField::new('end')
                ->setLabel('label.end'),

            MoneyField::new('startBid')
                ->setLabel('label.start_bid.label')
                ->setHelp('label.start_bid.help')
                ->setCurrency('EUR'),
            MoneyField::new('minimumBid')
                ->setLabel('label.minimum_bid.label')
                ->setHelp('label.minimum_bid.help')
                ->setCurrency('EUR'),
            BooleanField::new('isOnlyOneBidAllowed')
                ->setLabel('label.only_one_bid_allowed.label')
                ->setHelp('label.only_one_bid_allowed.help')
                ->hideOnIndex(),

            IntegerField::new('quantity')
                ->setLabel('label.quantity.label')
                ->setHelp('label.quantity.help'),

            FormField::addPanel('label.bids')
                ->onlyOnDetail(),

            CollectionField::new('bids')
                ->onlyOnDetail()
                ->setLabel('label.bids')
                ->renderExpanded(true)
                ->setEntryIsComplex(true)
                ->useEntryCrudForm(BidCrudController::class)
                ->setTemplatePath('admin/fields/auction/detail/bids.html.twig')
                ->setEntryToStringMethod(fn(Bid $bid) => $bid->getAmount()),
        ];
    }

}
