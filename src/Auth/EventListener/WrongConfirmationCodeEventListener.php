<?php

namespace App\Auth\EventListener;

use App\Auth\Event\WrongConfirmationCodeEvent;
use App\Auth\Repository\BannedPhoneRepository;
use App\Auth\Repository\ConfirmationTokenRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class WrongConfirmationCodeEventListener
{
    public function __construct(
        private ConfirmationTokenRepository $confirmationTokenRepository,
        private BannedPhoneRepository $bannedPhoneRepository,
    ) {
    }

    public function __invoke(WrongConfirmationCodeEvent $event)
    {
        if ($event->confirmationToken->getRetries() === 0) {
            $this->bannedPhoneRepository->banPhone($event->confirmationToken->getPhone());
            return;
        }
        $event->confirmationToken->decreaseRetries();
        $this->confirmationTokenRepository->save($event->confirmationToken);
    }
}
