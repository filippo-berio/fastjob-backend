<?php

namespace App\Core\Event\TaskResponse;

class UserRespondedEvent
{
    public function __construct(
        public int $taskResponseId
    ) {
    }
}
