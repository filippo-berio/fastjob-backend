<?php

namespace App\Core\Domain\TaskSchedule;

interface TaskSchedulerInterface
{
    public function dispatch(TaskInterface $task);
}
