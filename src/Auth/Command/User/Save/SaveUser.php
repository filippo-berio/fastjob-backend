<?php

namespace App\Auth\Command\User\Save;

use App\Auth\Entity\User;
use App\CQRS\BaseCommand;

class SaveUser extends BaseCommand
{
    public function __construct(public User $user)
    {
    }
}
