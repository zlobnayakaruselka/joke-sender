<?php
namespace App\Services\Validator;

use JsonSchema\Validator;

class JsonSchemaValidator implements JsonSchemaValidatorInterface
{
    /**
     * @var Validator
     */
    private $validator;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function isValid(string $json, string $jsonSchemaPath): bool
    {
        $jsonObject = json_decode($json);

        if (json_last_error() === JSON_ERROR_NONE) {
            $this->validator->validate($jsonObject, (object)['$ref' => 'file://' . realpath($jsonSchemaPath)]);
        }

        return $this->validator->isValid();
    }

    public function getErrors(): array
    {
        return $this->validator->getErrors();
    }
}