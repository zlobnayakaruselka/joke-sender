<?php
namespace App\Application\Event\Exception;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    /**
     * @var ExceptionResponseFactory
     */
    private $responseFactory;

    public function __construct(ExceptionResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $response = $this->responseFactory->createResponse($event->getException());

        $event->setResponse($response);
    }
}