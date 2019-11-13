<?php

namespace App\Components\JokeApi\Exception;

class ApiErrorException extends \Exception
{
    /**
     * @var int
     */
    private $statusCode;
    /**
     * @var string
     */
    private $apiErrorMessage;

    public function __construct(int $statusCode, string $apiErrorMessage = '', $code = 0, \Throwable $previous = null)
    {
        parent::__construct('Error in Joke API', $code, $previous);

        $this->statusCode = $statusCode;
        $this->apiErrorMessage = $apiErrorMessage;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getApiErrorMessage(): string
    {
        return $this->apiErrorMessage;
    }
}