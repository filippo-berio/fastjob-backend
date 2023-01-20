<?php

namespace App\Core\Events\EventListener\Auth;

use App\Core\DTO\Auth\ConfirmationData;
use App\Core\Events\Event\Auth\WrongConfirmationCodeEvent;
use App\Core\Service\Auth\Token\RedisTokenService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class WrongConfirmationCodeEventListener
{
    public function __construct(
        private RedisTokenService $redisTokenService,
    ) {
    }

    public function __invoke(WrongConfirmationCodeEvent $event)
    {
        if ($event->data->retries === 0) {
            $this->redisTokenService->banPhone($event->phone);
            return;
        }
        $data = new ConfirmationData($event->data->confirmationCode, $event->data->retries - 1);
        $this->redisTokenService->setConfirmationCode($data, $event->phone);
    }
}
