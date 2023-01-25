<?php

namespace App\Core\Infrastructure\Query\Profile;

use App\Auth\Entity\User as AuthUser;
use App\Auth\Query\User\FindById\FindUserById;
use App\Core\Infrastructure\Entity\Profile;
use App\CQRS\Bus\QueryBusInterface;

trait FillUserTrait
{
    protected function fillUser(Profile $profile, QueryBusInterface $queryBus): Profile
    {
        /** @var AuthUser $user */
        $user = $queryBus->query(new FindUserById($profile->getUserId()));
        return $profile->fillUser($user);
    }
}
