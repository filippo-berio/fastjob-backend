<?php

namespace App\Core\Infrastructure\Query\Profile;

use App\Auth\UseCase\User\GetUserByIdUseCase;
use App\Core\Domain\Entity\User;
use App\Core\Infrastructure\Entity\Profile;
use App\CQRS\Bus\QueryBusInterface;
use App\CQRS\QueryHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;

abstract class BaseProfileQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected GetUserByIdUseCase $getUserByIdUseCase,
    ) {
    }

    protected function fillProfile(Profile $profile): Profile
    {
        $user = $this->getUserByIdUseCase->get($profile->getUserId());
        $profile->setUser(new User($user->getId(), $user->getPhone()));
        return $profile;
    }
}
