<?php

namespace App\Api\Controller\Core;

use App\Api\Controller\BaseController;
use App\Api\Request\Profile\CreateProfileRequest;
use App\Api\Request\Profile\UpdateProfileRequest;
use App\Auth\Entity\User;
use App\Core\UseCase\Profile\CreateProfileUseCase;
use App\Core\UseCase\Profile\GetProfileUseCase;
use App\Core\UseCase\Profile\UpdateProfileUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/profile')]
class ProfileController extends BaseController
{
    #[Route(methods: ['GET'])]
    public function get(
        #[CurrentUser] User $user,
        GetProfileUseCase $useCase,
    ): JsonResponse {
        $profile = $useCase->get($user);
        return $this->json($profile, context: [
            'profile_full',
            'category_full',
            'city_full'
        ]);
    }

    #[Route(methods: ['POST'])]
    public function create(
        #[CurrentUser] User $user,
        CreateProfileRequest $body,
        CreateProfileUseCase $useCase,
    ): JsonResponse {
        $this->validator->validate($body);
        $useCase->create($user, $body->firstName, $body->birthDate);
        return $this->json([
            'success' => true
        ]);
    }

    #[Route(methods: ['PUT'])]
    public function update(
        #[CurrentUser] User $user,
        UpdateProfileRequest $body,
        UpdateProfileUseCase $useCase,
    ): JsonResponse {
        $this->validator->validate($body);
        $useCase->update(
            $user,
            $body->firstName,
            $body->categoryIds,
            $body->lastName,
            $body->description,
            $body->cityId,
        );
        return $this->json([
            'success' => true
        ]);
    }
}
