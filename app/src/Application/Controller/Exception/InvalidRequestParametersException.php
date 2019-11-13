<?php
namespace App\Application\Controller\Exception;

class InvalidRequestParametersException extends \InvalidArgumentException
{
    public function __construct($errors = [], $code = 0, \Throwable $previous = null)
    {
        $message = 'Request parameters is invalid' . PHP_EOL . json_encode($errors);

        parent::__construct($message, $code, $previous);
    }
}