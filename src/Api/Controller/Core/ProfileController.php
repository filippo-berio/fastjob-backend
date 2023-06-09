<?php

namespace App\Api\Controller\Core;

use App\Api\Controller\BaseController;
use App\Api\Gateway\CoreGateway;
use App\Api\Request\Profile\CreateProfileRequest;
use App\Api\Request\Profile\UpdateProfileRequest;
use App\Auth\Entity\User;
use App\Core\Application\UseCase\Profile\CreateProfileUseCase;
use App\Core\Application\UseCase\Profile\UpdateProfileUseCase;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\User as CoreUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/profile')]
class ProfileController extends BaseController
{
    #[Route(methods: ['GET'])]
    public function get(
        #[CurrentUser] User $user,
        CoreGateway $coreGateway,
    ): JsonResponse {
        $profile = $coreGateway->findProfileByAuthUser($user);
        return $this->json([
            'success' => !!$profile,
            'data' => $profile,
        ], context: [
            'profile_full',
            'category_full',
            'city_full'
        ]);
    }

    #[Route('/register', methods: ['POST'])]
    public function register(
        #[CurrentUser] User  $user,
        CreateProfileRequest $body,
        CreateProfileUseCase $useCase,
    ): JsonResponse {
        $this->validator->validate($body);
        $useCase->create(new CoreUser(
            $user->getId(),
            $user->getPhone(),
        ), $body->firstName, $body->birthDate);
        return $this->json([
            'success' => true
        ]);
    }

    #[Route('/update', methods: ['PUT'])]
    public function update(
        #[CurrentUser] Profile $profile,
        UpdateProfileRequest $body,
        UpdateProfileUseCase $useCase,
    ): JsonResponse {
        $this->validator->validate($body);
        $useCase->update(
            $profile,
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
