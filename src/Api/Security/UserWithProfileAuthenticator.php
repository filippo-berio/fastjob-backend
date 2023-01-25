<?php

namespace App\Api\Security;

use App\Auth\UseCase\AuthenticateUseCase;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Exception\Profile\ProfileNotFoundException;
use App\Core\Domain\Query\Profile\FindProfileByUser;
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
        /** @var Profile $profile */
        $profile = $this->queryBus->query(new FindProfileByUser($user));
        if (!$profile) {
            throw new ProfileNotFoundException();
        }
        return $profile;
    }
}
