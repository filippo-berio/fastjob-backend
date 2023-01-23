<?php

namespace App\Sms\Service;

use App\Sms\Exception\SmsServiceException;
use App\Sms\Service\Provider\SmsProviderFactory;
use App\Sms\Service\Provider\SmsProviderInterface;

class SmsService
{
    private const SMS_PROVIDER_RETRIES = 3;

    public function __construct(
        private SmsProviderInterface $provider,
    ) {
    }

    public function sendSms(string $phone, string $text)
    {
        for ($i = 0; $i < self::SMS_PROVIDER_RETRIES; $i++) {
            try {
                $this->provider->send($phone, $text);
                return;
            } catch (SmsServiceException $providerException) {
                continue;
            }
        }
        throw new SmsServiceException(previous: $providerException);
    }
}
