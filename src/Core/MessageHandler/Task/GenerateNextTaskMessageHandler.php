<?php

namespace App\Core\MessageHandler\Task;

use App\Core\Message\Task\GenerateNextTaskMessage;
use App\Core\Query\Profile\FindProfileById\FindProfileById;
use App\Core\Repository\ProfileNextTaskRepository;
use App\Core\Service\Task\NextTask\Generator\CategoryNextTaskGenerator;
use App\CQRS\Bus\QueryBusInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GenerateNextTaskMessageHandler
{
    public function __construct(
        private CategoryNextTaskGenerator $nextTaskGenerator,
        private ProfileNextTaskRepository $nextTaskRepository,
        private QueryBusInterface         $queryBus,
    ) {
    }

    public function __invoke(GenerateNextTaskMessage $message)
    {
        $profile = $this->queryBus->query(new FindProfileById($message->profileId));
        $tasks = $this->nextTaskGenerator->generateForProfile($profile, $message->count);
        $this->nextTaskRepository->add($profile, $tasks);
    }
}
