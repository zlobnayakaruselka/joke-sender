<?php
namespace tests\Unit\Components\JokeApi\Model\Builder;

use App\Components\JokeApi\Model\CategoriesResponseModel;
use App\Components\JokeApi\Model\Creator\Builder\ResponseModelBuilder;
use App\Components\JokeApi\Model\Creator\Builder\ResponseModelBuilderInterface;
use App\Components\JokeApi\Model\ResponseModelInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Components\JokeApi\Model\Creator\Builder\ResponseModelBuilder
 */
class ResponseModelBuilderTest extends TestCase
{
    /**
     * @var ResponseModelBuilderInterface
     */
    private $modelBuilder;
    /**
     * @var ResponseModelInterface | MockObject
     */
    private $responseModel;

    public function setUp(): void
    {
        parent::setUp();

        $this->responseModel = $this->getMockBuilder(ResponseModelInterface::class)
            ->setMethods(['getType', 'setType', 'getValue', 'setValue'])
            ->disableOriginalConstructor()->getMock();

        $this->modelBuilder = new ResponseModelBuilder();
    }

    /**
     * @covers ::<public>
     */
    public function testBuild()
    {
        $data = [
            'type' => 'success',
            'value' => ['cat1'],
            'other' => 'none'
        ];

        $this->responseModel->expects($this->once())->method('setType');
        $this->responseModel->expects($this->once())->method('setValue');

        $this->assertEquals($this->responseModel, $this->modelBuilder->build($this->responseModel, $data));
    }

    /**
     * @covers ::<public>
     */
    public function testBuildWithRealModel()
    {
        $data = [
            'type' => 'success',
            'value' => ['cat1'],
            'other' => 'none'
        ];

        $responseModel = $this->modelBuilder->build(new CategoriesResponseModel(), $data);

        $this->assertEquals($data['type'], $responseModel->getType());
        $this->assertEquals($data['value'], $responseModel->getValue());
    }
}