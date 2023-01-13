<?php

namespace App\Core\Data\Command\User;

use App\Core\Entity\User;
use App\CQRS\BaseCommand;

class SaveUser extends BaseCommand
{
    public function __construct(public User $user)
    {
    }
}
