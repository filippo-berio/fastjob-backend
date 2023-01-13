<?php

namespace App\Sms\Service\Provider;

interface SmsProviderInterface
{
    public function send(string $phone, string $text): void;

    public function getAlias(): string;
}
