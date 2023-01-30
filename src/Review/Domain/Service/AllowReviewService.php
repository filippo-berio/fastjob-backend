<?php

namespace App\Review\Domain\Service;

use App\Review\Domain\Contract\EntityMapperInterface;
use App\Review\Domain\Entity\Profile;
use App\Review\Domain\Entity\ReviewAvailability;
use App\Review\Domain\Repository\ReviewAvailabilityRepositoryInterface;

class AllowReviewService
{
    public function __construct(
        private ReviewAvailabilityRepositoryInterface $repository,
        private EntityMapperInterface $entityMapper,
    ) {
    }

    public function allow(Profile $author, Profile $target, bool $allowComment)
    {
        $entity = $this->entityMapper->persistenceEntity(ReviewAvailability::class);
        $availability = $this->repository->find($author, $target) ?? new $entity(
            $author, $target
        );
        if (!$availability->isAllowComment()) {
            $availability->setAllowComment($allowComment);
        }
        $this->repository->save($availability);
    }
}
