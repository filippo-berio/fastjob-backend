<?php

namespace App\Review\Infrastructure\Service;

use App\Review\Domain\Contract\EntityMapperInterface;
use App\Review\Domain\Entity\Review;
use App\Review\Domain\Entity\ReviewAvailability;

class EntityMapper implements EntityMapperInterface
{

    public function persistenceEntity(string $domainEntity): string
    {
        return [
            Review::class => \App\Review\Infrastructure\Entity\Review::class,
            ReviewAvailability::class => \App\Review\Infrastructure\Entity\ReviewAvailability::class
        ][$domainEntity] ?? $domainEntity;
    }
}
