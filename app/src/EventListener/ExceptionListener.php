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

        switch (get_class($exception)) {
            case InvalidRequestParametersException::class:
                $response = $this->createResponse(
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    $exception->getMessage() . PHP_EOL . json_encode($exception->getErrors())
                );
                break;
            case InvalidResponseException::class:
            case ApiErrorException::class:
            case TransportExceptionInterface::class:
                $response = $this->createResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $exception->getMessage());
                break;
            default:
                $response = $this->createResponse(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something was wrong');
        }

        $event->setResponse($response);
    }

    private function createResponse(int $statusCode, string $content): Response
    {
        return new Response($content, $statusCode);
    }
}