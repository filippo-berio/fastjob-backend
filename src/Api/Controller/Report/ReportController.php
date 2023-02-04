<?php

namespace App\Api\Controller\Report;

use App\Api\Controller\BaseController;
use App\Review\Application\UseCase\CreateReviewUseCase;
use App\Review\Application\UseCase\GetProfileReviewsUseCase;
use Symfony\Component\Routing\Annotation\Route;

#[Route('review')]
class ReportController extends BaseController
{
    #[Route]
    public function action(
        GetProfileReviewsUseCase $useCase2,
        CreateReviewUseCase      $reportUseCase,
    )
    {

    }
}
