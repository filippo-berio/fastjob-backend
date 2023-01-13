<?php

namespace App\Sms\Service;

use App\Sms\Exception\SmsServiceException;
use App\Sms\Service\Provider\SmsProviderFactory;

class SmsService
{
    private const SMS_PROVIDER_RETRIES = 3;

    public function __construct(
        private SmsProviderFactory $providerFactory,
        private string $provider,
    ) {
    }

    public function sendSms(string $phone, string $text)
    {
        $providerService = $this->providerFactory->create($this->provider);
        for ($i = 0; $i < self::SMS_PROVIDER_RETRIES; $i++) {
            try {
                $providerService->send($phone, $text);
                return;
            } catch (SmsServiceException $providerException) {
                continue;
            }
        }
        throw new SmsServiceException(previous: $providerException);
    }
}
