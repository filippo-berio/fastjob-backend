<?php

namespace App\Core\Domain\Event\Task\Offer;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Event\EventInterface;

readonly class TaskOfferEvent implements EventInterface
{
    public function __construct(
        public Task $task,
        public Profile $executor,
    ) {
    }
}
