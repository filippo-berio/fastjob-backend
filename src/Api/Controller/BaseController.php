<?php

namespace App\Api\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Context\Normalizer\DateTimeNormalizerContextBuilder;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseController extends AbstractController
{
    public function __construct(
        protected ValidatorInterface $validator
    ) {
    }

    public function json(
        mixed $data,
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
            ->toArray();

        return parent::json($data, $status, $headers, $context);
    }
}
