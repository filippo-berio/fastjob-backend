<?php

namespace App\Lib\Sms\Message;


class SmsMessage
{
    public function __construct(
        private string $phone,
        private string $text,
    ) {
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
