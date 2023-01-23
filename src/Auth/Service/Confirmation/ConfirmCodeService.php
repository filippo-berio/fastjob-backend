<?php

namespace App\Auth\Service\Confirmation;

use App\Auth\DTO\TokenPair;
use App\Auth\Event\WrongConfirmationCodeEvent;
use App\Auth\Exception\InvalidConfirmationCodeException;
use App\Auth\Exception\PhoneBannedException;
use App\Auth\Query\User\FindByPhone\FindUserByPhone;
use App\Auth\Repository\BannedPhoneRepository;
use App\Auth\Repository\ConfirmationTokenRepository;
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
        private ConfirmationTokenRepository $confirmationTokenRepository,
        private BannedPhoneRepository $bannedPhoneRepository,
    ) {
    }

    public function confirm(string $phone, string $code): TokenPair
    {
        $this->validateCode($phone, $code);

        $this->confirmationTokenRepository->delete($phone);

        $user = $this->queryBus->query(new FindUserByPhone($phone));
        if (!$user) {
            $user = $this->registerUserService->register($phone);
        }

        return $this->loginUserService->login($user);
    }

    private function validateCode(string $phone, string $code)
    {
        if ($this->bannedPhoneRepository->isPhoneBanned($phone)) {
            throw new PhoneBannedException();
        }

        $actualCode = $this->confirmationTokenRepository->findByPhone($phone);
        if ($actualCode?->getConfirmationCode() !== $code) {
            if ($actualCode) {
                $this->eventDispatcher->dispatch(new WrongConfirmationCodeEvent($actualCode));
            }
            throw new InvalidConfirmationCodeException();
        }
    }
}
