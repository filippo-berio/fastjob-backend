<?php

namespace App\Core\Domain\DTO\Address;

readonly class AddressPlain
{
    public function __construct(
        public int $cityId,
        public string $title,
    ) {
    }
}
