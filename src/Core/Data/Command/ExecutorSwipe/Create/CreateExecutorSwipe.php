<?php

namespace App\Core\Data\Command\ExecutorSwipe\Create;

use App\Core\Entity\ExecutorSwipe;
use App\CQRS\BaseCommand;

class CreateExecutorSwipe extends BaseCommand
{
    public function __construct(
        public ExecutorSwipe $executorSwipe
    ) {
    }
}
