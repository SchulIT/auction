<?php

namespace App\Twig;

use App\Auction\BidManager;
use App\Entity\Auction;
use App\Entity\Bid;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AuctionExtension extends AbstractExtension {

    public function __construct(private readonly BidManager $bidManager) {

    }

    public function getFunctions(): array {
        return [
            new TwigFunction('highestBid', [$this, 'getHighestBid']),
        ];
    }

    public function getHighestBid(Auction $auction): ?Bid {
        return $this->bidManager->getHighestBid($auction);
    }
}