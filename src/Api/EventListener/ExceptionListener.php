<?php

namespace App\Api\EventListener;

use App\Auth\Exception\BaseException as AuthException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            // the priority must be greater than the Security HTTP
            // ExceptionListener, to make sure it's called before
            // the default exception listener
            KernelEvents::EXCEPTION => [
                'onKernelException',
                0,
            ],
        ];
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $customCode = 1;

        if ($exception instanceof AuthException) {
            $customCode = $exception->customCode;
        }

        if (method_exists($exception, 'getStatusCode')) {
            $code = $exception->getStatusCode();
        } else {
            $code = $exception->getCode() ?: 500;
        }

        $event->setResponse(new JsonResponse([
            'success' => false,
            'error'   => $exception->getMessage(),
            'code'    => $customCode,
        ], $code));
    }

}
