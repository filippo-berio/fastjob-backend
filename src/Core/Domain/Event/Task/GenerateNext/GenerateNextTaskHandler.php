<?php

namespace App\Core\Domain\Event\Task\GenerateNext;

use App\Core\Domain\Event\EventHandlerInterface;
use App\Core\Domain\Event\EventInterface;
use App\Core\Domain\Query\Profile\FindProfileById;
use App\Core\Domain\Repository\ProfileNextTaskRepositoryInterface;
use App\Core\Domain\Service\Task\NextTask\Generator\CategoryNextTaskGenerator;
use App\CQRS\Bus\QueryBusInterface;

class GenerateNextTaskHandler implements EventHandlerInterface
{
    public function __construct(
        private CategoryNextTaskGenerator          $nextTaskGenerator,
        private ProfileNextTaskRepositoryInterface $nextTaskRepository,
        private QueryBusInterface                  $queryBus,
        private int                                $taskStackLimit,
    ) {
    }

    public function executionType(): string
    {
        return self::EXECUTION_TYPE_ASYNC;
    }

    public function event(): string
    {
        return GenerateNextTaskEvent::class;
    }

    /**
     * @param GenerateNextTaskEvent $event
     * @return void
     */
    public function handle(EventInterface $event)
    {
        $profile = $this->queryBus->query(new FindProfileById($event->profileId));
        $tasks = $this->nextTaskGenerator->generateForProfile($profile, $event->count ?? $this->taskStackLimit);
        $this->nextTaskRepository->add($profile, $tasks);
    }
}
