<?php
namespace App\Application\Event\Exception;

use Symfony\Component\HttpFoundation\Response;

class ExceptionResponseFactory
{
    private const DEFAULT_MESSAGE = 'Something was wrong';
    private const DEFAULT_STATUS_CODE = Response::HTTP_INTERNAL_SERVER_ERROR;

    /**
     * @var array
     */
    private $exceptionErrorMap;

    public function __construct(array $exceptionErrorMap)
    {
        $this->exceptionErrorMap = $exceptionErrorMap;
    }

    public function createResponse(\Exception $exception): Response
    {
        $exceptionClass = get_class($exception);

        if (isset($this->exceptionErrorMap[$exceptionClass])) {
            $message = $this->exceptionErrorMap[$exceptionClass]['message'] .
                (
                    ($this->exceptionErrorMap[$exceptionClass]['add_exception_message'] ?? false)
                    ? PHP_EOL . $exception->getMessage()
                    : ''
                );

            $statusCode = $this->exceptionErrorMap[$exceptionClass]['http_status_code'];
        }

        return new Response($message ?? self::DEFAULT_MESSAGE, $statusCode ?? self::DEFAULT_STATUS_CODE);
    }
}