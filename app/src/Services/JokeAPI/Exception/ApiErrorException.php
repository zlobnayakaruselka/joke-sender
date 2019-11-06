<?php

namespace App\Services\JokeAPI\Exception;

use Throwable;

class ApiErrorException extends \Exception
{
    /**
     * @var int
     */
    private $statusCode;
    /**
     * @var string
     */
    private $statusBody;
    
    public function __construct($statusCode = 500, $statusBody = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct('Joke API returned error', $code, $previous);
        
        $this->statusCode = $statusCode;
    }
    
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getStatusBody(): string
    {
        return $this->statusBody;
    }

}