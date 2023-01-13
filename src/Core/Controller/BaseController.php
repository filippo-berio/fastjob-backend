<?php

namespace App\Core\Controller;

use App\Core\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseController extends AbstractController
{
    public function __construct(
        protected ValidatorInterface $validator
    ) {
    }

    protected function makeResponseTokenHeaders(User $user): array
    {
        return [
            'x-access-token' => $user->getAccessToken(),
            'x-refresh-token' => $user->getRefreshToken(),
        ];
    }
}
