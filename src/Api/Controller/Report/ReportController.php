<?php

namespace App\Api\Controller\Report;

use App\Report\Application\UseCase\AllowReviewUseCase;
use App\Report\Application\UseCase\CreateReviewUseCase;
use App\Report\Application\UseCase\GetProfileReviewsUseCase;

class ReportController
{
    public function action(
        AllowReviewUseCase       $useCase1,
        GetProfileReviewsUseCase $useCase2,
        CreateReviewUseCase      $reportUseCase,
    )
    {

    }
}
