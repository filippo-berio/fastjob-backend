<?php

namespace App\Core\Infrastructure\Query\Profile;

use App\Core\Domain\Entity\User;
use App\Core\Infrastructure\Entity\Profile;
use App\Core\Infrastructure\Gateway\UserGateway;
use App\Lib\CQRS\QueryHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;

abstract class BaseProfileQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected UserGateway $userGateway,
    ) {
    }

    protected function fillProfile(Profile $profile): Profile
    {
        $externalUser = $this->userGateway->findUser($profile->getUserId());
        $profile->setUser(new User($externalUser->getId(), $externalUser->getPhone()));
        return $profile;
    }
}
