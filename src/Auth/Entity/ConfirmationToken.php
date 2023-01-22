<?php

namespace App\Auth\Entity;

class ConfirmationToken
{
    public function __construct(
        private string $phone,
        private string $confirmationCode,
        private int $retries,
    ) {
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
