<?php
namespace App\EventListener;

use App\Controller\Exception\InvalidRequestParametersException;
use App\Services\JokeAPI\Exception\ApiErrorException;
use App\Services\JokeAPI\Exception\InvalidResponseException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getException();

        // Customize your response object to display the exception details
        $response = new Response();

        if ($exception instanceof InvalidRequestParametersException) {
            $response->setContent($exception->getMessage() . PHP_EOL . json_encode($exception->getErrors()));
            $response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        } elseif (
            $exception instanceof InvalidResponseException
            | $exception instanceof ApiErrorException
            | $exception instanceof \RuntimeException
        ) {
            $response->setContent('The joke API is in trouble right now. Try again later');
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        } elseif ($exception instanceof TransportExceptionInterface) {
            $response->setContent('We can\'t send an email. Problems with the mail server. Try again later');
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        } else {
            // \Exception
            $response->setContent('Something was wrong');
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $event->setResponse($response);
    }
}