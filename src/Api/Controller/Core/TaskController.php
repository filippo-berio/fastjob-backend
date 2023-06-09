<?php

namespace App\Api\Controller\Core;

use App\Api\Controller\BaseController;
use App\Api\Request\Task\CreateTaskRequest;
use App\Api\Request\Task\MakeOfferRequest;
use App\Core\Application\UseCase\Review\CreateExecutorReviewUseCase;
use App\Core\Application\UseCase\Review\GetAvailableReviewTargetsUseCase;
use App\Core\Application\UseCase\Task\CancelTaskExecutionUseCase;
use App\Core\Application\UseCase\Task\CreateTaskUseCase;
use App\Core\Application\UseCase\Task\FinishTaskUseCase;
use App\Core\Application\UseCase\Task\OfferTaskUseCase;
use App\Core\Application\UseCase\TaskOffer\AcceptOfferUseCase;
use App\Core\Application\UseCase\TaskOffer\RejectTaskOfferUseCase;
use App\Core\Domain\DTO\Address\AddressPlain;
use App\Core\Domain\Entity\Profile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/task')]
class TaskController extends BaseController
{
    #[Route('', methods: ['POST'])]
    public function create(
        #[CurrentUser] Profile $profile,
        CreateTaskUseCase      $useCase,
        CreateTaskRequest      $body,
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


    #[Route('/offer', methods: ['POST'])]
    public function offer(
        #[CurrentUser] Profile $profile,
        MakeOfferRequest $body,
        OfferTaskUseCase $useCase,
    ): JsonResponse {
        $useCase->offer($profile, $body->taskId, $body->executorId);
        return $this->json([
            'success' => true
        ]);
    }

    public function acceptOffer(
        #[CurrentUser] Profile    $profile,
        AcceptOfferUseCase $useCase,
        RejectTaskOfferUseCase $rejectTaskOfferUseCase,
        FinishTaskUseCase $finishTaskUseCase,
        CancelTaskExecutionUseCase $cancelTaskExecutionUseCase,
        GetAvailableReviewTargetsUseCase $getAvailableReviewTargetsUseCase,
        CreateExecutorReviewUseCase $createExecutorReviewUseCase,
    ): JsonResponse {
        $tasks = $useCase->get($profile);
        return $this->json($tasks, context: ['task_full']);
    }
}
