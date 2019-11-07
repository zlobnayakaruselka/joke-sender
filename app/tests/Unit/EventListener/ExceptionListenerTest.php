<?php
namespace tests\Unit\EventListener;

use App\Controller\Exception\InvalidRequestParametersException;
use App\EventListener\ExceptionListener;
use App\Services\JokeAPI\Exception\ApiErrorException;
use App\Services\JokeAPI\Exception\InvalidResponseException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Mailer\Exception\TransportException;

/**
 * @coversDefaultClass \App\EventListener\ExceptionListener
 */
class ExceptionListenerTest extends TestCase
{
    /**
     * @var ExceptionListener
     */
    private $exceptionListener;

    public function setUp(): void
    {
        parent::setUp();

        $this->exceptionListener = new ExceptionListener();
    }

    public function dataOnKernelException()
    {
        return [
            [InvalidRequestParametersException::class, 422, 'Request parameters is invalid
[]'],
            [InvalidResponseException::class, 500, 'Joke API response is invalid'],
            [ApiErrorException::class, 500, 'Joke API returned error'],
            [TransportException::class, 500, 'Something was wrong'],
            [\RuntimeException::class, 500, 'Something was wrong'],
            [\Exception::class, 500, 'Something was wrong'],
            [\LogicException::class, 500, 'Something was wrong'],
        ];
    }

    /**
     * @dataProvider dataOnKernelException
     * @covers ::onKernelException
     * @covers ::createResponse
     */
    public function testOnKernelException(string $exceptionClass, int $statusCode, string $message): void
    {
        /** @var HttpKernelInterface | MockObject $kernel */
        $kernel = $this->getMockBuilder(HttpKernelInterface::class)->disableOriginalConstructor()->getMock();
        /** @var Request | MockObject $request */
        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();

        $exception = new $exceptionClass();
        $event = new ExceptionEvent($kernel, $request, 1, $exception);

        $this->exceptionListener->onKernelException($event);

        $this->assertEquals($statusCode, $event->getResponse()->getStatusCode());
        $this->assertEquals($message, $event->getResponse()->getContent());
    }
}