<?php
namespace App\Services\Validator;

interface JsonSchemaValidatorInterface
{
    public function isValid(string $json, string $jsonSchemaPath): bool;

    public function getErrors(): array;
}