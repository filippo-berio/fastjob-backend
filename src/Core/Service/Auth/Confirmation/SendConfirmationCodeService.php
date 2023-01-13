<?php

namespace App\Core\Service\Auth\Confirmation;

use App\Core\Service\Auth\Token\RedisTokenService;
use App\Sms\Service\SmsService;
use Symfony\Component\Messenger\MessageBusInterface;

class SendConfirmationCodeService
{
    public function __construct(
        private RedisTokenService $redisTokenService,
        // TODO
        private MessageBusInterface $messageBus,
        private SmsService $smsService,
    ) {
    }

    public function send(string $phone, string $code)
    {
        $this->redisTokenService->setConfirmationCode($code, $phone);
        $this->smsService->sendSms($phone, $code);
    }
}
