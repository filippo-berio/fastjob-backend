<?php

namespace App\Core\Infrastructure\Query\Profile;

use App\Auth\Entity\User as AuthUser;
use App\Auth\Query\User\FindById\FindUserById;
use App\Core\Domain\Entity\User;
use App\Core\Infrastructure\Entity\Profile;
use App\CQRS\Bus\QueryBusInterface;
use App\CQRS\QueryHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;

abstract class BaseProfileQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected QueryBusInterface $queryBus,
    ) {
    }

    protected function fillProfile(Profile $profile): Profile
    {
        // todo usecase
        /** @var AuthUser $user */
        $user = $this->queryBus->query(new FindUserById($profile->getUserId()));
        $profile->setUser(new User($user->getId(), $user->getPhone()));
        return $profile;
    }
}
