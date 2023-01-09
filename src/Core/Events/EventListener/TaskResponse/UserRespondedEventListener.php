<?php

namespace App\Core\Events\EventListener\TaskResponse;

use App\Core\Event\TaskResponse\UserRespondedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: UserRespondedEvent::class)]
class UserRespondedEventListener
{
    public function __invoke(UserRespondedEvent $event)
    {

    }
}
