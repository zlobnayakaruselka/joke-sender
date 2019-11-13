<?php

namespace App\Components\JokeApi\Exception;

use Throwable;

class InvalidResponseException extends \Exception
{
    /**
     * @var string[]
     */
    private $errors = [];

    public function __construct(array $errors, $code = 0, Throwable $previous = null)
    {
        parent::__construct('Error in Joke API', $code, $previous);

        $this->errors = $errors;
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}