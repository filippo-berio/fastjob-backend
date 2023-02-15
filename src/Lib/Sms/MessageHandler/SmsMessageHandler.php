<?php

namespace App\Lib\Sms\MessageHandler;

use App\Lib\Sms\Message\SmsMessage;
use App\Lib\Sms\Service\SmsService;
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
