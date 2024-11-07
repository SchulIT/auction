<?php

namespace App\Security\Voter;

use App\Entity\Auction;
use LogicException;
use SchulIT\CommonBundle\Helper\DateHelper;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AuctionVoter extends Voter {

    public const BID = 'bid';

    public function __construct(private readonly DateHelper $dateHelper) {

    }

    protected function supports(string $attribute, mixed $subject): bool {
        return $attribute === self::BID && $subject instanceof Auction;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool {
        switch($attribute) {
            case self::BID:
                return $this->canBid($subject);
        }

        throw new LogicException('This code should not be reached!');
    }

    public function canBid(Auction $auction): bool {
        $now = $this->dateHelper->getNow();

        return $auction->getStart() <= $now && $now <= $auction->getEnd();
    }
}