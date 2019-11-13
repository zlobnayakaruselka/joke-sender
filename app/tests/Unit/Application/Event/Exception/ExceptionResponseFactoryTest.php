<?php
namespace tests\Unit\Application\Event\Exception;

use App\Application\Event\Exception\ExceptionResponseFactory;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Application\Event\Exception\ExceptionResponseFactory
 */
class ExceptionResponseFactoryTest extends TestCase
{
    public function dataCreateResponse()
    {
        return [
            [\InvalidArgumentException::class, 'Unprocessable Entity' . PHP_EOL . 'added_message', 422],
            [\LogicException::class, 'ERROR', 502],
            [\RuntimeException::class, 'ERROR_1', 503],
            [\Exception::class, 'Something was wrong', 500]
        ];
    }

    /**
     * @dataProvider dataCreateResponse
     * @covers ::<public>
     */
    public function testCreateResponse($exceptionClass, $message, $statusCode)
    {
        $exceptionErrorMap = [
            \InvalidArgumentException::class => [
                'http_status_code' => 422,
                'message' => 'Unprocessable Entity',
                'add_exception_message' => true,
            ],
            \LogicException::class => [
                'http_status_code' => 502,
                'message' => 'ERROR',
                'add_exception_message' => false,
            ],
            \RuntimeException::class => [
                'http_status_code' => 503,
                'message' => 'ERROR_1',
            ]
        ];

        $exception = new $exceptionClass('added_message');

        $factory = new ExceptionResponseFactory($exceptionErrorMap);

        $response = $factory->createResponse($exception);

        $this->assertEquals($statusCode, $response->getStatusCode());
        $this->assertEquals($message, $response->getContent());
    }
}