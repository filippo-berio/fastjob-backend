<?php

namespace App\Auth\Service\Confirmation;

use App\Auth\DTO\CodeConfirmationResult;
use App\Auth\Event\WrongConfirmationCodeEvent;
use App\Auth\Exception\InvalidConfirmationCodeException;
use App\Auth\Exception\PhoneBannedException;
use App\Auth\Query\User\FindByPhone\FindUserByPhone;
use App\Auth\Repository\BannedPhoneRepository;
use App\Auth\Repository\ConfirmationTokenRepository;
use App\Auth\Service\User\LoginUserService;
use App\Auth\Service\User\RegisterUserService;
use App\Lib\CQRS\Bus\QueryBusInterface;
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

    public function confirm(string $phone, string $code): CodeConfirmationResult
    {
        $retriesLeft = $this->validateCode($phone, $code);
        if ($retriesLeft !== null) {
            return CodeConfirmationResult::buildFailure($retriesLeft);
        }

        $this->confirmationTokenRepository->delete($phone);

        $user = $this->queryBus->query(new FindUserByPhone($phone));
        if (!$user) {
            $user = $this->registerUserService->register($phone);
        }

        $tokens = $this->loginUserService->login($user);

        return CodeConfirmationResult::buildSuccess($tokens);
    }

    private function validateCode(string $phone, string $code): ?int
    {
        if ($this->bannedPhoneRepository->isPhoneBanned($phone)) {
            throw new PhoneBannedException();
        }

        $actualCode = $this->confirmationTokenRepository->findByPhone($phone);
        if ($actualCode?->getConfirmationCode() !== $code) {
            if ($actualCode) {
                $retries = $actualCode->getRetries();
                $this->eventDispatcher->dispatch(new WrongConfirmationCodeEvent($actualCode));
                return $retries - 1;
            }

            throw new InvalidConfirmationCodeException();
        }

        return null;
    }
}
