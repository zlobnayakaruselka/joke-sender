<?php
namespace App\Components\Services\Validator\Object;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SymfonyObjectValidator implements ObjectValidatorInterface
{
    /**
     * @var ConstraintViolationListInterface
     */
    private $errors;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function isValid($object): bool
    {
        $this->errors = $this->validator->validate($object);

        return $this->errors->count() === 0;
    }

    public function getErrors():array
    {
        $errorMessages = [];

        /** @var ConstraintViolationInterface $error */
        foreach ($this->errors->getIterator() as $error){
            $errorMessages[] = $error->getMessage();
        }

        return $errorMessages;
    }
}