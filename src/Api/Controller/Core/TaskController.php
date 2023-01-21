<?php

namespace App\Api\Controller\Core;

use App\Api\Controller\BaseController;
use App\Api\Request\Task\CreateTaskRequest;
use App\Core\DTO\Address\AddressPlain;
use App\Core\Entity\Profile;
use App\Core\UseCase\Task\CreateTaskUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/task')]
class TaskController extends BaseController
{
    #[Route('', methods: ['POST'])]
    public function create(
        #[CurrentUser] Profile $profile,
        CreateTaskUseCase $useCase,
        CreateTaskRequest $body,
    ): JsonResponse {
        $this->validator->validate($body);
        $useCase->create(
            $profile,
            $body->title,
            $body->categoryIds,
            $body->description,
            $body->price,
            $body->address ? new AddressPlain(
                $body->address['cityId'],
                $body->address['title'],
            ) : null,
            $body->deadline,
        );
        return $this->json([
            'success' => true
        ], headers: $this->makeResponseTokenHeaders($profile->getUser()));
    }
}
