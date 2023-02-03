<?php

namespace App\Review\Infrastructure\Repository;

use App\Review\Domain\Entity\Profile as DomainProfile;
use App\Review\Domain\Entity\Review as DomainReview;
use App\Review\Domain\Repository\ReviewRepositoryInterface;
use App\Review\Infrastructure\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;

class ReviewRepository implements ReviewRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function findByTarget(DomainProfile $target): array
    {
        return $this->entityManager->getRepository(Review::class)
            ->createQueryBuilder('r')
            ->andWhere('r.targetId = :target')
            ->setParameter('target', $target->getId())
            ->getQuery()
            ->getResult();
    }

    public function save(DomainReview $review): DomainReview
    {
        $this->entityManager->persist($review);
        $this->entityManager->flush();
        return $review;
    }

    public function find(int $id): ?DomainReview
    {
        return $this->entityManager->getRepository(Review::class)
            ->find($id);
    }
}
