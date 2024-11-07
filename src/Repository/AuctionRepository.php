<?php

namespace App\Repository;

use App\Entity\Auction;
use DateTime;
use Symfony\Component\Validator\Constraints\Date;

class AuctionRepository extends AbstractRepository implements AuctionRepositoryInterface {

    public function findAll(): array {
        return $this->em->createQueryBuilder()
            ->select(['a', 'b'])
            ->from(Auction::class, 'a')
            ->leftJoin('a.bids', 'b')
            ->orderBy('a.end', 'asc')
            ->getQuery()
            ->getResult();
    }

    public function findActive(DateTime $now): array {
        return $this->em->createQueryBuilder()
            ->select(['a', 'b'])
            ->from(Auction::class, 'a')
            ->leftJoin('a.bids', 'b')
            ->where('a.start <= :now')
            ->andWhere('a.end >= :now')
            ->orderBy('a.end', 'asc')
            ->setParameter('now', $now)
            ->getQuery()
            ->getResult();
    }

    public function findPast(DateTime $now): array {
        return $this->em->createQueryBuilder()
            ->select(['a', 'b'])
            ->from(Auction::class, 'a')
            ->leftJoin('a.bids', 'b')
            ->where('a.end < :now')
            ->orderBy('a.end', 'asc')
            ->setParameter('now', $now)
            ->getQuery()
            ->getResult();
    }

    public function findUpcoming(DateTime $now): array {
        return $this->em->createQueryBuilder()
            ->select(['a', 'b'])
            ->from(Auction::class, 'a')
            ->leftJoin('a.bids', 'b')
            ->where('a.start > :now')
            ->orderBy('a.end', 'asc')
            ->setParameter('now', $now)
            ->getQuery()
            ->getResult();
    }

    public function persist(Auction $auction): void {
        $this->em->persist($auction);
        $this->em->flush();
    }

    public function remove(Auction $auction): void {
        $this->em->remove($auction);
        $this->em->flush();
    }

    public function count(): int {
        return $this->em->createQueryBuilder()
            ->select('COUNT(a.id)')
            ->from(Auction::class, 'a')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countActive(DateTime $now): int {
        return $this->em->createQueryBuilder()
            ->select('COUNT(a.id)')
            ->from(Auction::class, 'a')
            ->where('a.start <= :now')
            ->andWhere('a.end >= :now')
            ->orderBy('a.end', 'asc')
            ->setParameter('now', $now)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countPast(DateTime $now): int {
        return $this->em->createQueryBuilder()
            ->select('COUNT(a.id)')
            ->from(Auction::class, 'a')
            ->where('a.end < :now')
            ->orderBy('a.end', 'asc')
            ->setParameter('now', $now)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countUpcoming(DateTime $now): int {
        return $this->em->createQueryBuilder()
            ->select('COUNT(a.id)')
            ->from(Auction::class, 'a')
            ->where('a.start > :now')
            ->orderBy('a.end', 'asc')
            ->setParameter('now', $now)
            ->getQuery()
            ->getSingleScalarResult();
    }
}