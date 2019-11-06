<?php

namespace App\Services\JokeAPI\Exception;

use Throwable;

class InvalidResponseException extends \Exception
{
    /**
     * @var array
     */
    private $errors;

    public function __construct($errors = [], $code = 0, Throwable $previous = null)
    {
        parent::__construct('Joke API response is invalid', $code, $previous);

        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}