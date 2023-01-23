<?php

namespace App\Api\Controller\Core;

use App\Api\Controller\BaseController;
use App\Api\Request\Task\CreateTaskRequest;
use App\Core\DTO\Address\AddressPlain;
use App\Core\Entity\Profile;
use App\Core\UseCase\Task\CreateTaskUseCase;
use App\Core\UseCase\Task\GetProfileNextTaskUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
            $body->remote,
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
        ]);
    }

    #[Route('/next', methods: ['GET'])]
    public function getNext(
        #[CurrentUser] Profile $profile,
        Request $request,
        GetProfileNextTaskUseCase $useCase,
    ): JsonResponse {
        $count = $request->query->get('count');
        $tasks = $count ?
            $useCase->get($profile, $count) :
            $useCase->get($profile);
        return $this->json($tasks, context: ['task_full']);
    }
}
