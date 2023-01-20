<?php

namespace App\Auth\DTO;

readonly class ConfirmationData
{
    public function __construct(
        public string $confirmationCode,
        public int $retries,
    ) {
    }

    public function __toString(): string
    {
        return "$this->confirmationCode-$this->retries";
    }

    public static function fromString(string $str)
    {
        $parts = explode('-', $str);
        return new static($parts[0], $parts[1]);
    }
}
