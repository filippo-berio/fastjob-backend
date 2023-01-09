<?php

namespace App\Core\Events\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
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
//        $exception = $event->getThrowable();
//
//        if (!$exception instanceof \Exception) {
//            return;
//        }
//        // todo response code
//        $code     = $exception instanceof HttpException ? $exception->getStatusCode() : $exception->getCode();
//        dd($exception);
//        $response = new JsonResponse([
//            'success' => false,
//            'error'   => $exception->getMessage(),
//        ], $code ?? 500);
//        $event->setResponse($response);
    }

}
