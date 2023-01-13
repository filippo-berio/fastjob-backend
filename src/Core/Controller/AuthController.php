<?php

namespace App\Core\Controller;

use App\Core\DTO\Request\Auth\ConfirmCodeRequest;
use App\Core\DTO\Request\Auth\SendConfirmCodeRequest;
use App\Core\UseCase\Auth\ConfirmCodeUseCase;
use App\Core\UseCase\Auth\SendConfirmationCodeUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends BaseController
{
    #[Route('/send-code', methods: ['POST'])]
    public function sendConfirmationCode(
        SendConfirmCodeRequest $body,
        SendConfirmationCodeUseCase $useCase,
    ): JsonResponse {
        $this->validator->validate($body);
        $useCase->send($body->phone);
        return $this->json([
            'success' => true
        ]);
    }

    #[Route('/confirm-code', methods: ['POST'])]
    public function confirmCode(
        ConfirmCodeRequest $body,
        ConfirmCodeUseCase $useCase,
    ): JsonResponse {
        $tokens = $useCase->confirm($body->phone, $body->code);
        return $this->json($tokens);
    }
}
