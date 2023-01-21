<?php

namespace App\Auth\Service\Confirmation;

use App\Auth\Entity\ConfirmationToken;
use App\Auth\Exception\PhoneBannedException;
use App\Auth\Repository\BannedPhoneRepository;
use App\Auth\Repository\ConfirmationTokenRepository;
use App\Sms\UseCase\SendSmsUseCase;

class SendConfirmationCodeService
{
    const RETRIES = 5;

    public function __construct(
        private SendSmsUseCase $sendSmsUseCase,
        private ConfirmationTokenRepository $confirmationTokenRepository,
        private BannedPhoneRepository $bannedPhoneRepository,
    ) {
    }

    public function send(string $phone)
    {
        if ($this->bannedPhoneRepository->isPhoneBanned($phone)) {
            throw new PhoneBannedException();
        }
        $current = $this->confirmationTokenRepository->findByPhone($phone);
        $code = rand(1111, 9999);

        $this->confirmationTokenRepository->save(
            $current?->setConfirmationCode($code) ?? new ConfirmationToken($phone, $code, self::RETRIES)
        );

        $this->sendSmsUseCase->send($phone, $code);
    }
}
