<?php

namespace App\Core\Controller;

use App\Core\Security\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseController extends AbstractController
{
    public function __construct(
        protected ValidatorInterface $validator
    ) {
    }

    protected function makeResponseTokenHeaders(UserInterface $user): array
    {
        return [
            'x-access-token' => $user->getAccessToken(),
            'x-refresh-token' => $user->getRefreshToken(),
        ];
    }
}
