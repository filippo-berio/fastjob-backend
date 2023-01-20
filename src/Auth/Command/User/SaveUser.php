<?php

namespace App\Auth\Command\User;

use App\Auth\Entity\User;
use App\CQRS\BaseCommand;

class SaveUser extends BaseCommand
{
    public function __construct(public User $user)
    {
    }
}
