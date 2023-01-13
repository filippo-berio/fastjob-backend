<?php

namespace App\Core\Service\Auth\Confirmation;

use App\Core\Data\Query\User\FindUserByPhone;
use App\Core\DTO\TokenPair;
use App\Core\Exception\Auth\InvalidConfirmationCodeException;
use App\Core\Service\Auth\Token\RedisTokenService;
use App\Core\Service\User\LoginUserService;
use App\Core\Service\User\RegisterUserService;
use App\CQRS\Bus\QueryBusInterface;

class ConfirmCodeService
{
    public function __construct(
        private RedisTokenService $redisTokenService,
        private QueryBusInterface $queryBus,
        private RegisterUserService $registerUserService,
        private LoginUserService $loginUserService,
    ) {
    }

    public function confirm(string $phone, string $code): TokenPair
    {
        $actualCode = $this->redisTokenService->getConfirmationCode($phone);
        if ($actualCode !== $code) {
            throw new InvalidConfirmationCodeException();
        }

        $user = $this->queryBus->handle(new FindUserByPhone($phone));
        if (!$user) {
            $user = $this->registerUserService->register($phone);
        }

        return $this->loginUserService->login($user);
    }
}
