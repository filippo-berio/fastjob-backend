<?php

namespace App\Core\Service\Auth\Confirmation;

use App\Core\DTO\Auth\ConfirmationData;
use App\Core\Exception\Auth\PhoneBannedException;
use App\Core\Service\Auth\Token\RedisTokenService;
use App\Sms\Service\SmsService;

class SendConfirmationCodeService
{
    const RETRIES = 5;

    public function __construct(
        // TODO async
        private SmsService $smsService,
        private RedisTokenService $redisTokenService,
    ) {
    }

    public function send(string $phone)
    {
        if ($this->redisTokenService->isPhoneBanned($phone)) {
            throw new PhoneBannedException();
        }
        $current = $this->redisTokenService->getConfirmationCode($phone);
        $code = rand(1111, 9999);
        $this->redisTokenService->setConfirmationCode(
            new ConfirmationData($code, $current?->retries ?? self::RETRIES),
            $phone
        );
        $this->smsService->sendSms($phone, $code);
    }
}
