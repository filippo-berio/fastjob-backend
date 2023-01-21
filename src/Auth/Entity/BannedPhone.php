<?php

namespace App\Auth\Entity;

class BannedPhone
{
    public function __construct(
        private string $phone
    ) {
    }

    public function getPhone(): string
    {
        return $this->phone;
    }
}
