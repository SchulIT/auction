<?php

namespace App\Controller;

use App\Entity\Auction;
use App\Repository\AuctionRepositoryInterface;
use SchulIT\CommonBundle\Helper\DateHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController {

    #[Route('', name: 'dashboard')]
    public function index(AuctionRepositoryInterface $auctionRepository, DateHelper $dateHelper): Response {
        $now = $dateHelper->getNow();
        $past = $auctionRepository->findPast($now);
        $upcoming = $auctionRepository->findUpcoming($now);
        $active = $auctionRepository->findActive($now);

        $compareFunc = function (Auction $a, Auction $b)  {
            return $a->getEnd() <=> $b->getEnd();
        };

        usort($past, $compareFunc);
        usort($upcoming, $compareFunc);
        usort($active, $compareFunc);

        return $this->render('dashboard/index.html.twig', [
            'auctions' => array_merge($active, $upcoming, $past),
            'now' => $dateHelper->getNow(),
        ]);
    }
}