<?php

namespace App\Api\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('ping')]
class PingController extends BaseController
{
    #[Route]
    public function ping(): JsonResponse
    {
        return $this->json([
            'message' => 'fastjob running...'
        ]);
    }
}
