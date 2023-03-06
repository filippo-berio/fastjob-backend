<?php

namespace App\Api\Controller;

use App\Api\Service\AccessTokenContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Context\Normalizer\DateTimeNormalizerContextBuilder;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseController extends AbstractController
{
    public function __construct(
        protected ValidatorInterface $validator,
        protected AccessTokenContext $accessTokenContext,
    ) {
    }

    public function json(
        mixed $data = [
            'success' => true
        ],
        int $status = 200,
        array $headers = [],
        array $context = []
    ): JsonResponse {
//        Определяем по-умолчнию, для конкретных полей можно переопределить через атрибут
//        #[Context(normalizationContext: [DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i'])]
        $contextBuilder = (new DateTimeNormalizerContextBuilder())
            ->withFormat('Y-m-d');
        $context = (new ObjectNormalizerContextBuilder())
            ->withContext($contextBuilder)
            ->withGroups($context)
            ->withEnableMaxDepth(true)
            ->withCircularReferenceLimit(3)
            ->toArray();

        $headers = [
            ...$headers,
            ...$this->makeResponseTokenHeaders(),
        ];
        return parent::json($data, $status, $headers, $context);
    }

    protected function makeResponseTokenHeaders(): array
    {
        if ($this->accessTokenContext->has()) {
            [$accessToken, $refreshToken] = $this->accessTokenContext->get();
            return [
                'x-access-token' => $accessToken,
                'x-refresh-token' => $refreshToken,
            ];
        }
        return [];
    }
}
