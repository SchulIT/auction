<?php

namespace App\Entity;

use DateTime;
use DH\Auditor\Provider\Doctrine\Auditing\Annotation\Auditable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[Auditable]
class Auction {
    use IdTrait;

    use UuidTrait;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private ?string $title;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    private ?string $description;

    #[ORM\Column(nullable: true)]
    private ?string $imageFileName = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\GreaterThan(0)]
    private int $startBid = 0;

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\GreaterThan(0)]
    private int $minimumBid = 1;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isOnlyOneBidAllowed = false;

    #[Column(type: Types::INTEGER)]
    #[Assert\GreaterThanOrEqual(1)]
    private int $quantity = 1;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    #[Assert\NotNull]
    private DateTime $start;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    #[Assert\NotNull]
    #[Assert\GreaterThan(propertyPath: 'start')]
    private DateTime $end;

    #[ORM\OneToMany(targetEntity: Bid::class, mappedBy: 'auction', orphanRemoval: true)]
    #[ORM\OrderBy(['amount' => 'DESC'])]
    private Collection $bids;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $updatedAt = null;

    public function __construct() {
        $this->uuid = Uuid::v4()->toString();
        $this->bids = new ArrayCollection();
    }

    /**
     * @return Collection<Bid>
     */
    public function getBids(): Collection {
        return $this->bids;
    }

    public function getTitle(): ?string {
        return $this->title;
    }

    public function setTitle(?string $title): void {
        $this->title = $title;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): void {
        $this->description = $description;
    }

    public function getStartBid(): int {
        return $this->startBid;
    }

    public function setStartBid(int $startBid): void {
        $this->startBid = $startBid;
    }

    public function getMinimumBid(): int {
        return $this->minimumBid;
    }

    public function setMinimumBid(int $minimumBid): void {
        $this->minimumBid = $minimumBid;
    }

    public function isOnlyOneBidAllowed(): bool {
        return $this->isOnlyOneBidAllowed;
    }

    public function setIsOnlyOneBidAllowed(bool $isOnlyOneBidAllowed): void {
        $this->isOnlyOneBidAllowed = $isOnlyOneBidAllowed;
    }

    public function getStart(): DateTime {
        return $this->start;
    }

    public function setStart(DateTime $start): void {
        $this->start = $start;
    }

    public function getEnd(): DateTime {
        return $this->end;
    }

    public function setEnd(DateTime $end): void {
        $this->end = $end;
    }

    public function getImageFileName(): ?string {
        return $this->imageFileName;
    }

    public function setImageFileName(?string $imageFileName): void {
        $this->imageFileName = $imageFileName;
    }

    public function getQuantity(): int {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void {
        $this->quantity = $quantity;
    }

    public function __toString(): string {
        return sprintf('%s #%d', $this->getTitle(), $this->getId());
    }
}