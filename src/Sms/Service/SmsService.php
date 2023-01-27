<?php

namespace App\Sms\Service;

use App\Sms\Exception\SmsServiceException;
use App\Sms\Service\Provider\SmsProviderFactory;
use App\Sms\Service\Provider\SmsProviderInterface;

class SmsService
{
    private const SMS_PROVIDER_RETRIES = 3;

    public function __construct(
        private SmsProviderFactory $providerFactory,
    ) {
    }

    public function sendSms(string $phone, string $text)
    {
        $provider = $this->providerFactory->create();
//        dd($provider);
        for ($i = 0; $i < self::SMS_PROVIDER_RETRIES; $i++) {
            try {
                $provider->send($phone, $text);
                return;
            } catch (SmsServiceException $providerException) {
                continue;
            }
        }
        throw new SmsServiceException(previous: $providerException);
    }
}
