<?php

namespace App\Core\Infrastructure\TaskSchedule\Task;

use App\Core\Domain\TaskSchedule\Task\GenerateNextTask;
use App\Core\Domain\TaskSchedule\Task\GenerateNextTaskHandler as DomainHandler;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(handles: GenerateNextTask::class)]
class GenerateNextTaskHandler extends DomainHandler
{
}
