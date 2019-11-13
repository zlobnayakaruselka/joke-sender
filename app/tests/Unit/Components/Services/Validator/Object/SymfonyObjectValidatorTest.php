<?php
namespace tests\Unit\Components\Services\Validator\Object;

use App\Components\Services\Validator\Object\ObjectValidatorInterface;
use App\Components\Services\Validator\Object\SymfonyObjectValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @coversDefaultClass \App\Components\Services\Validator\Object\SymfonyObjectValidator
 */
class SymfonyObjectValidatorTest extends TestCase
{
    /**
     * @var ConstraintViolationListInterface | MockObject
     */
    private $errors;
    /**
     * @var ConstraintViolationInterface | MockObject
     */
    private $error;
    /**
     * @var ValidatorInterface | MockObject
     */
    private $validator;
    /**
     * @var ObjectValidatorInterface
     */
    private $objectValidator;

    public function setUp(): void
    {
        parent::setUp();

        $this->validator = $this->getMockBuilder(ValidatorInterface::class)
            ->disableOriginalConstructor()->getMock();
        $this->errors = $this->getMockBuilder(ConstraintViolationListInterface::class)
            ->setMethods([
                'getIterator', 'addAll', 'get', 'add', 'has', 'set', 'remove', 'offsetExists',
                'count', 'offsetGet', 'offsetSet', 'offsetUnset'
            ])
            ->disableOriginalConstructor()->getMock();
        $this->error = $this->getMockBuilder(ConstraintViolationInterface::class)
            ->disableOriginalConstructor()->getMock();

        $this->objectValidator = new SymfonyObjectValidator($this->validator);
    }

    public function dataIsValid()
    {
        return [
            [0, true],
            [1, false],
            [5, false]
        ];
    }

    /**
     * @dataProvider dataIsValid
     * @covers ::<public>
     */
    public function testIsValid($count, $result)
    {
        $object = new \stdClass();

        $this->errors->expects($this->once())->method('count')->willReturn($count);
        $this->validator->expects($this->once())->method('validate')->with($object)->willReturn($this->errors);

        $this->assertEquals($result, $this->objectValidator->isValid($object));
    }

    /**
     * @covers ::getErrors
     */
    public function testGetErrors()
    {
        $this->errors->expects($this->once())->method('getIterator')->willReturn([$this->error]);
        $this->error->expects($this->once())->method('getMessage')->willReturn('Error_message');
        $this->validator->expects($this->once())->method('validate')->willReturn($this->errors);

        $this->objectValidator->isValid(new \stdClass());
        $this->assertEquals(['Error_message'], $this->objectValidator->getErrors());
    }
//    public function isValid($object): bool
//    {
//        $this->errors = $this->validator->validate($object);
//
//        return $this->errors->count() === 0;
//    }
//
//    public function getErrors():array
//    {
//        $errorMessages = [];
//
//        /** @var ConstraintViolationInterface $error */
//        foreach ($this->errors->getIterator() as $error){
//            $errorMessages[] = $error->getMessage();
//        }
//
//        return $errorMessages;
//    }
}