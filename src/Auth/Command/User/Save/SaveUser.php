<?php

namespace App\Auth\Command\User\Save;

use App\Auth\Entity\User;
use App\Lib\CQRS\CommandInterface;

class SaveUser implements CommandInterface
{
    public function __construct(public User $user)
    {
    }
}
