<?php

namespace App\Auth\Entity;

class ConfirmationToken
{
    public function __construct(
        public string $phone,
        public string $confirmationCode,
        public int $retries,
    ) {
    }

    public static function fromString(string $str)
    {
        $parts = explode('-', $str);
        return new static($parts[0], $parts[1]);
    }

    public function setConfirmationCode(string $confirmationCode): self
    {
        $this->confirmationCode = $confirmationCode;
        return $this;
    }

    public function decreaseRetries()
    {
        $this->retries--;
    }

    public function getConfirmationCode(): string
    {
        return $this->confirmationCode;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getRetries(): int
    {
        return $this->retries;
    }
}
