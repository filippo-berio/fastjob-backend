<?php

namespace App\Core\Service\Auth\Confirmation;

use App\Core\Data\Query\User\FindUserByPhone;
use App\Core\DTO\TokenPair;
use App\Core\Events\Event\Auth\WrongConfirmationCodeEvent;
use App\Core\Exception\Auth\InvalidConfirmationCodeException;
use App\Core\Exception\Auth\PhoneBannedException;
use App\Core\Service\Auth\Token\RedisTokenService;
use App\Core\Service\User\LoginUserService;
use App\Core\Service\User\RegisterUserService;
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
