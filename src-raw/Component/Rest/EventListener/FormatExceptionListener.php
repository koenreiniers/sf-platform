<?php
namespace Raw\Component\Rest\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FormatExceptionListener
{
    public function onRestException(GetResponseForExceptionEvent $event)
    {

        $exception = $event->getException();

        $statusCode = 500;

        if($exception instanceof HttpException) {
            $statusCode = $exception->getStatusCode();
        }

        $data = [
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
        ];

        $response = new JsonResponse($data, $statusCode);

        $event->setResponse($response);
    }
}