<?php

namespace App\Sms\UseCase;

use App\Sms\Message\SmsMessage;
use Symfony\Component\Messenger\MessageBusInterface;

class SendSmsUseCase
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function send(string $phone, string $text)
    {
        $this->messageBus->dispatch(new SmsMessage($phone, $text));
    }
}
