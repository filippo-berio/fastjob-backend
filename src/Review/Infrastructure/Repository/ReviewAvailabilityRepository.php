<?php

namespace App\Review\Infrastructure\Repository;

use App\Review\Domain\Entity\Profile;
use App\Review\Domain\Entity\ReviewAvailability as DomainReviewAvailability;
use App\Review\Infrastructure\Entity\ReviewAvailability;
use App\Review\Domain\Repository\ReviewAvailabilityRepositoryInterface;
use Doctrine\ODM\MongoDB\DocumentManager;

class ReviewAvailabilityRepository implements ReviewAvailabilityRepositoryInterface
{
    public function __construct(
        private DocumentManager $documentManager,
    ) {
    }

    public function save(DomainReviewAvailability $reviewAvailability): DomainReviewAvailability
    {
        $this->documentManager->persist($reviewAvailability);
        $this->documentManager->flush();
        return $reviewAvailability;
    }

    public function find(Profile $author, Profile $target): ?DomainReviewAvailability
    {
        return $this->documentManager->getRepository(ReviewAvailability::class)
            ->findOneBy([
                'authorId' => $author->getId(),
                'targetId' => $target->getId(),
            ]);

    }

    public function delete(DomainReviewAvailability $reviewAvailability)
    {
        $this->documentManager->remove($reviewAvailability);
        $this->documentManager->flush();
    }
}
