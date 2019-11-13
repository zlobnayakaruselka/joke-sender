<?php
namespace App\Application\Event\Exception;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    /**
     * @var ExceptionResponseFactory
     */
    private $responseFormatter;

    public function __construct(ExceptionResponseFactory $responseFormatter)
    {
        $this->responseFormatter = $responseFormatter;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $response = $this->responseFormatter->formatResponse($event->getException());

        $event->setResponse($response);
    }
}