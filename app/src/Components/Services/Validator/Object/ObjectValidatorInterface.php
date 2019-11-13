<?php
namespace App\Components\Services\Validator\Object;

interface ObjectValidatorInterface
{
    public function isValid($object): bool;

    public function getErrors():array;
}