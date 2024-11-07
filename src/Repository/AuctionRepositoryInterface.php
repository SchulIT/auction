<?php

namespace App\Repository;

use App\Entity\Auction;
use DateTime;

interface AuctionRepositoryInterface {
    public function findAll(): array;

    /**
     * @param DateTime $now
     * @return Auction[]
     */
    public function findActive(DateTime $now): array;

    /**
     * @param DateTime $now
     * @return Auction[]
     */
    public function findPast(DateTime $now): array;

    /**
     * @param DateTime $now
     * @return Auction[]
     */
    public function findUpcoming(DateTime $now): array;

    public function count(): int;

    public function countActive(DateTime $now): int;

    public function countPast(DateTime $now): int;

    public function countUpcoming(DateTime $now): int;

    public function persist(Auction $auction): void;

    public function remove(Auction $auction): void;
}