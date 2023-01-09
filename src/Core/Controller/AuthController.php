<?php

namespace App\Core\Controller;

use App\Core\DTO\Request\Auth\LoginRequest;
use App\Core\UseCase\Auth\LoginUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('auth')]
class AuthController extends BaseController
{
    #[Route('/login', methods: ['POST'])]
    public function login(
        LoginRequest $data,
        LoginUseCase $useCase,
    ): JsonResponse {
        $this->validator->validate($data);
        $tokens = $useCase($data->login, $data->password);
        return $this->json($tokens);
    }

    #[Route('/register', methods: ['POST'])]
    public function register()
    {

    }
}
