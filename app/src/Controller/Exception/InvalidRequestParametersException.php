<?php
namespace App\Controller\Exception;

class InvalidRequestParametersException extends \Exception
{
    /**
     * @var array
     */
    private $errors;

    public function __construct($errors = [], $code = 0, \Throwable $previous = null)
    {
        parent::__construct('Request parameters is invalid', $code, $previous);

        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}