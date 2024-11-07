<?php

namespace App\Auction;

use App\Entity\Auction;
use App\Entity\Bid;
use App\Entity\User;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Money\Money;
use Psr\Log\LoggerInterface;
use SchulIT\CommonBundle\Helper\DateHelper;

readonly class BidManager {
    public function __construct(private EntityManagerInterface $em, private DateHelper $dateHelper, private LoggerInterface $logger) {

    }

    /**
     * @param Auction[] $auctions
     * @return int
     */
    public function sumAllWinningBids(array $auctions): int {
        $sum = 0;

        foreach($auctions as $auction) {
            $winningBids = $this->getHighestBids($auction);

            foreach($winningBids as $bid) {
                $sum += $bid->getAmount();
            }
        }

        return $sum;
    }

    /**
     * @throws InvalidAmountException
     * @throws AuctionNotActiveException
     */
    public function placeBid(Bid $bid): void {
        if($bid->getAuction() === null) {
            throw new InvalidArgumentException('$bid->getAuction() must not be null.');
        }

        if(!$this->dateHelper->isBetween($this->dateHelper->getNow(), $bid->getAuction()->getStart(), $bid->getAuction()->getEnd())) {
            throw new AuctionNotActiveException();
        }

        try {
            // lock database for
            $tableName = $this->em->getClassMetadata(Auction::class)->getTableName();
            $this->em->getConnection()->executeQuery(sprintf('LOCK TABLE %s WRITE', $tableName));

            // VALIDATE
            $minimumBid = $this->getMinimumAmountForNewBid($bid->getAuction(), $bid->getUser());
            if($minimumBid > $bid->getAmount()) {
                throw new InvalidAmountException($minimumBid, $bid->getAmount());
            }

            if($bid->getAuction()->isOnlyOneBidAllowed()) {
                // remove previous bids
                $this->removePreviousBids($bid->getAuction(), $bid->getUser());
            }

            // INSERT
            $this->em->persist($bid);
            $this->em->flush();

            $this->em->getConnection()->executeQuery('UNLOCK TABLE');
        } catch(Exception $e) {
            $this->logger->error($e->getMessage(), [
                'exception' => $e,
            ]);
        }
    }

    public function getHighestBid(Auction $auction): ?Bid {
        $highestBids = $this->getHighestBids($auction);

        if(count($highestBids) === 0) {
            return null;
        }

        return $highestBids[count($highestBids) - 1];
    }

    /**
     * @param Auction $auction
     * @return Bid[]
     */
    public function getBidsFromHighestToLowest(Auction $auction): array {
        $bids = $auction->getBids()->toArray();
        usort($bids, function (Bid $a, Bid $b) {
            return $a->getAmount() <=> $b->getAmount();
        });
        return array_reverse($bids);
    }

    /**
     * Gets the first N highest bids, based on the auction type
     *
     * @param Auction $auction
     * @return Bid[]
     */
    public function getHighestBids(Auction $auction): array {
        $bids = $this->getBidsFromHighestToLowest($auction);
        $bids = array_slice($bids, 0, $auction->getQuantity());

        if(count($bids) === 0) {
            return [];
        }

        return $bids;
    }

    public function getHighestBidForUser(Auction $auction, User $user): ?Bid {
        $bids = $this->getBidsFromHighestToLowest($auction);

        foreach($bids as $bid) {
            if($bid->getUser()->getId() === $user->getId()) {
                return $bid;
            }
        }

        return null;
    }

    public function getMinimumAmountForNewBid(Auction $auction, User $user): int {
        $highestBid = $this->getHighestBid($auction);
        $highestAmount = $highestBid !== null ? $highestBid->getAmount() : $auction->getStartBid();

        $highestUserBid = $this->getHighestBidForUser($auction, $user)?->getAmount();

        return max($highestAmount, $highestUserBid) + $auction->getMinimumBid();
    }

    private function removePreviousBids(Auction $auction, User $user): void {
        $bids = $auction->getBids()->toArray();

        foreach($bids as $bid) {
            if($bid->getUser()->getId() === $user->getId()) {
                $auction->getbids()->removeElement($bid);
            }
        }
    }
}