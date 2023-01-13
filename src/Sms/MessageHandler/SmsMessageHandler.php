<?php

namespace App\Sms\MessageHandler;

use App\Sms\Message\SmsMessage;
use App\Sms\Service\SmsService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SmsMessageHandler
{
    public function __construct(
        private readonly SmsService $smsService,
    ) {
    }

    public function __invoke(SmsMessage $message)
    {
        $this->smsService->sendSms(
            $message->getPhone(),
            $message->getText(),
        );
    }

}
