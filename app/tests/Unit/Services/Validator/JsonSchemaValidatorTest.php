<?php
namespace tests\Unit\Services\Validator;

use App\Services\Validator\JsonSchemaValidator;
use JsonSchema\Validator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Services\Validator\JsonSchemaValidator
 */
class JsonSchemaValidatorTest extends TestCase
{
    /**
     * @var Validator | MockObject
     */
    private $validatorMock;
    /**
     * @var JsonSchemaValidator
     */
    private $jsonSchemaValidator;

    public function setUp(): void
    {
        parent::setUp();

        $this->validatorMock = $this->getMockBuilder(Validator::class)
            ->disableOriginalConstructor()->getMock();
        
        $this->jsonSchemaValidator = new JsonSchemaValidator($this->validatorMock);
    }

    public function dataisValid()
    {
        return [
            ['{"a": "b"}', false, true],
            ['{"a": "b",}', true, false],
            ['{"a": "c"}', false, false],
        ];
    }

    /**
     * @dataProvider dataIsValid
     * @covers ::isValid
     * @covers ::__construct
     */
    public function testIsValid(string $json, bool $jsonDecodeErrorExist, bool $isValid): void
    {
        $jsonSchema = 'path';

        $this->validatorMock->expects($this->exactly((int)!$jsonDecodeErrorExist))
            ->method('validate');
        $this->validatorMock->expects($this->exactly((int)!$jsonDecodeErrorExist))
            ->method('isValid')
            ->willReturn($isValid);

        $this->assertEquals($isValid, $this->jsonSchemaValidator->isValid($json, $jsonSchema));
    }

    public function dataGetErrors(): array
    {
        return [
            [['err1', 'err2']],
            [[]]
        ];
    }

    /**
     * @dataProvider dataGetErrors
     * @covers ::getErrors
     */
    public function testGetErrors(array $errors): void
    {
        $this->validatorMock->expects($this->once())
            ->method('getErrors')
            ->willReturn($errors);

        $this->assertEquals($errors, $this->jsonSchemaValidator->getErrors());
    }
}