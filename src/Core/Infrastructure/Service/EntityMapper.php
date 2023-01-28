<?php

namespace App\Core\Infrastructure\Service;

use App\Core\Domain\Contract\EntityMapperInterface;
use App\Core\Domain\Entity\Category;
use App\Core\Domain\Entity\ExecutorSwipe;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\TaskSwipe;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Entity\TaskOffer;

class EntityMapper implements EntityMapperInterface
{
    public function persistenceEntity(string $domainEntity): string
    {
        return [
            Category::class => \App\Core\Infrastructure\Entity\Category::class,
            ExecutorSwipe::class => \App\Core\Infrastructure\Entity\ExecutorSwipe::class,
            Profile::class => \App\Core\Infrastructure\Entity\Profile::class,
            Task::class => \App\Core\Infrastructure\Entity\Task::class,
            TaskSwipe::class => \App\Core\Infrastructure\Entity\TaskSwipe::class,
            TaskOffer::class => \App\Core\Infrastructure\Entity\TaskOffer::class,
        ][$domainEntity] ?? $domainEntity;
    }
}
