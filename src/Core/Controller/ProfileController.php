<?php

namespace App\Core\Controller;

use App\Core\DTO\Request\Profile\CreateProfileRequest;
use App\Core\Entity\User;
use App\Core\UseCase\Profile\CreateProfileUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/profile')]
class ProfileController extends BaseController
{
    #[Route(methods: ['POST'])]
    public function create(
        #[CurrentUser] User $user,
        CreateProfileRequest $body,
        CreateProfileUseCase $useCase,
    ): JsonResponse {
//        dd($body);
        $this->validator->validate($body);
        $useCase->create($user, $body->firstName, $body->birthDate);
        return $this->json([
            'success' => true
        ]);
    }
}
