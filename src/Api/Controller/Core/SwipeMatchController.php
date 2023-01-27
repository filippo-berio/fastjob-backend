<?php

namespace App\Api\Controller\Core;

use App\Api\Controller\BaseController;
use App\Core\Application\UseCase\SwipeMatch\GetAuthorMatchesUseCase;
use App\Core\Application\UseCase\SwipeMatch\GetExecutorMatchesUseCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SwipeMatchController extends BaseController
{
    public function getExecutorMatches(
        GetExecutorMatchesUseCase $useCase
    ) {

    }

    public function getAuthorMatches(
        GetAuthorMatchesUseCase $useCase
    ) {

    }
}
