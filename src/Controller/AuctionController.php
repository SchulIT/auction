<?php

namespace App\Controller;

use App\Auction\AuctionNotActiveException;
use App\Auction\BidManager;
use App\Auction\InvalidAmountException;
use App\Entity\Auction;
use App\Entity\Bid;
use App\Entity\User;
use App\Form\BidType;
use App\Repository\AuctionRepositoryInterface;
use App\Security\Voter\AuctionVoter;
use SchulIT\CommonBundle\Helper\DateHelper;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/auction')]
class AuctionController extends AbstractController {

    #[Route(name: 'auctions')]
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

    #[Route('/{uuid}', name: 'show_auction')]
    public function showAuction(#[MapEntity(mapping: ['uuid' => 'uuid'])] Auction $auction,
                                #[CurrentUser] User $user,
                                Request $request,
                                DateHelper $dateHelper,
                                BidManager $bidManager): Response {
        $isActive = $dateHelper->isBetween($dateHelper->getNow(), $auction->getStart(), $auction->getEnd());
        $form = null;
        $minimumAmount = $bidManager->getMinimumAmountForNewBid($auction, $user);

        if($this->isGranted(AuctionVoter::BID, $auction)) {
            $bid = (new Bid())
                ->setAuction($auction)
                ->setUser($user)
                ->setAmount($minimumAmount);
            $form = $this->createForm(BidType::class, $bid);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                try {
                    $bidManager->placeBid($bid);

                    $this->addFlash('success', 'auction.bid.success');
                } catch (InvalidAmountException $a) {
                    $this->addFlash('error', 'auction.bid.invalid_amount');
                } catch (AuctionNotActiveException $e) {
                    $this->addFlash('error', 'auction.bid.not_active');
                }

                return $this->redirectToRoute('show_auction', ['uuid' => $bid->getAuction()->getUuid()]);
            }
        }

        return $this->render('auction/show.html.twig', [
            'auction' => $auction,
            'bids' => $bidManager->getHighestBids($auction),
            'form' => $form?->createView(),
            'isActive' => $isActive,
            'minimumAmount' => $minimumAmount
        ]);
    }

}