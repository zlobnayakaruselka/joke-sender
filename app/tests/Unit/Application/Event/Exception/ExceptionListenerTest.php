<?php
namespace tests\Unit\Application\Event\Exception\EventListener;

use App\Application\Event\Exception\ExceptionListener;
use App\Application\Event\Exception\ExceptionResponseFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * @coversDefaultClass \App\Application\Event\Exception\ExceptionListener
 */
class ExceptionListenerTest extends TestCase
{
    /**
     * @var ExceptionResponseFactory
     */
    private $responseFactory;
    /**
     * @var ExceptionListener
     */
    private $exceptionListener;

    public function setUp(): void
    {
        parent::setUp();

        $this->responseFactory = $this->getMockBuilder(ExceptionResponseFactory::class)
            ->disableOriginalConstructor()->getMock();

        $this->exceptionListener = new ExceptionListener($this->responseFactory);
    }

    /**
     * @covers ::<public>
     */
    public function testOnKernelException(): void
    {
        $response = $this->getMockBuilder(Response::class)->disableOriginalConstructor()->getMock();
        /** @var ExceptionEvent | MockObject $event */
        $event = $this->getMockBuilder(ExceptionEvent::class)->disableOriginalConstructor()->getMock();
        $exception = $this->getMockBuilder(\Exception::class)->disableOriginalConstructor()->getMock();

        $event->expects($this->once())->method('getException')->willReturn($exception);
        $event->expects($this->once())->method('setResponse')->with($response);

        $this->responseFactory->expects($this->once())->method('createResponse')->with($exception)->willReturn($response);

        $this->exceptionListener->onKernelException($event);
    }
}