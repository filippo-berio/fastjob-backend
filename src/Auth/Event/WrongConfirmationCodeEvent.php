<?php

namespace App\Auth\Event;

use App\Auth\DTO\ConfirmationData;

class WrongConfirmationCodeEvent
{
    public function __construct(
        public string $phone,
        public ConfirmationData $data,
    ) {
    }
}
