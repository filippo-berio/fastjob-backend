<?php

namespace App\Auth\Event;

use App\Auth\Entity\ConfirmationToken;

class WrongConfirmationCodeEvent
{
    public function __construct(
        public ConfirmationToken $confirmationToken,
    ) {
    }
}
