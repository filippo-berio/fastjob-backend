<?php

namespace App\Core\Events\Event\Auth;

use App\Core\DTO\Auth\ConfirmationData;

class WrongConfirmationCodeEvent
{
    public function __construct(
        public string $phone,
        public ConfirmationData $data,
    ) {
    }
}
