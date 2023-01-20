<?php

namespace App\Auth\Service\Confirmation;

use App\Auth\DTO\TokenPair;
use App\Auth\Event\WrongConfirmationCodeEvent;
use App\Auth\Exception\InvalidConfirmationCodeException;
use App\Auth\Exception\PhoneBannedException;
use App\Auth\Query\User\FindUserByPhone;
use App\Auth\Service\Token\RedisTokenService;
use App\Auth\Service\User\LoginUserService;
use App\Auth\Service\User\RegisterUserService;
use App\CQRS\Bus\QueryBusInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ConfirmCodeService
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private EventDispatcherInterface $eventDispatcher,
        private RegisterUserService $registerUserService,
        private LoginUserService $loginUserService,
        private RedisTokenService $redisTokenService,
    ) {
    }

    public function confirm(string $phone, string $code): TokenPair
    {
        $this->validateCode($phone, $code);

        $this->redisTokenService->deleteConfirmationCode($phone);

        $user = $this->queryBus->handle(new FindUserByPhone($phone));
        if (!$user) {
            $user = $this->registerUserService->register($phone);
        }

        return $this->loginUserService->login($user);
    }

    private function validateCode(string $phone, string $code)
    {
        if ($this->redisTokenService->isPhoneBanned($phone)) {
            throw new PhoneBannedException();
        }

        $actualCode = $this->redisTokenService->getConfirmationCode($phone);
        if ($actualCode?->confirmationCode !== $code) {
            if ($actualCode) {
                $this->eventDispatcher->dispatch(new WrongConfirmationCodeEvent($phone, $actualCode));
            }
            throw new InvalidConfirmationCodeException();
        }
    }
}
