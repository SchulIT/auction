<?php

namespace App\Entity;

use App\Validation\GreaterThanHighestBid;
use DateTimeImmutable;
use DH\Auditor\Provider\Doctrine\Auditing\Annotation\Auditable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[Auditable]
class Bid {

    use IdTrait;
    use UuidTrait;

    #[ORM\ManyToOne(targetEntity: Auction::class, inversedBy: 'bids')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Auction $auction;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\GreaterThan(0)]
    #[GreaterThanHighestBid]
    private int $amount;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private DateTimeImmutable $createdAt;

    public function __construct() {
        $this->uuid = Uuid::v4()->toString();
    }

    public function getAuction(): Auction {
        return $this->auction;
    }

    public function setAuction(Auction $auction): Bid {
        $this->auction = $auction;
        return $this;
    }

    public function getUser(): User {
        return $this->user;
    }

    public function setUser(User $user): Bid {
        $this->user = $user;
        return $this;
    }

    public function getAmount(): int {
        return $this->amount;
    }

    public function setAmount(int $amount): Bid {
        $this->amount = $amount;
        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable {
        return $this->createdAt;
    }
}