<?php

namespace App\Controller\Admin;

use App\Auction\BidManager;
use App\Entity\Auction;
use App\Entity\Bid;
use App\Entity\User;
use App\Repository\AuctionRepositoryInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use SchulIT\CommonBundle\Helper\DateHelper;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private readonly AuctionRepositoryInterface $auctionRepository,
                                private readonly BidManager $bidManager,
                                private readonly DateHelper $dateHelper) {

    }


    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'count' => $this->auctionRepository->count(),
            'countActive' => $this->auctionRepository->countActive($this->dateHelper->getNow()),
            'countPast' => $this->auctionRepository->countPast($this->dateHelper->getNow()),
            'countUpcoming' => $this->auctionRepository->countUpcoming($this->dateHelper->getNow()),
            'sumWinningBids' => $this->bidManager->sumAllWinningBids($this->auctionRepository->findAll())
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('SchulIT Auktion')
            ->setLocales(['de']);
    }

    public function configureAssets(): Assets {
        return Assets::new()
            ->addWebpackEncoreEntry('admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Übersicht', 'fa fa-home');
        yield MenuItem::linkToCrud('Auktionen', 'fas fa-list', Auction::class);
        yield MenuItem::linkToCrud('Gebote', 'fas fa-list', Bid::class);
        yield MenuItem::linkToCrud('Benutzer', 'fas fa-users', User::class);
    }
}
