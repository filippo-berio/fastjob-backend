<?php

namespace App\Api\Security;

use App\Auth\Entity\User;
use App\Auth\UseCase\AuthenticateUseCase;
use App\Core\Exception\Profile\ProfileNotFoundException;
use App\Core\Query\Profile\FindByUser\FindProfileByUser;
use App\CQRS\Bus\QueryBusInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class UserWithProfileAuthenticator extends AccessRefreshTokenAuthenticator
{
    public function __construct(
        AuthenticateUseCase $authenticateUseCase,
        private QueryBusInterface $queryBus,
    ) {
        parent::__construct($authenticateUseCase);
    }

    protected function getUser(Request $request): UserInterface
    {
        $user = parent::getUser($request);
        $profile = $this->queryBus->handle(new FindProfileByUser($user));
        if (!$profile) {
            throw new ProfileNotFoundException();
        }
        return $profile;
    }
}
