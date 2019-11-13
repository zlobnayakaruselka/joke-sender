<?php
namespace tests\Unit\Components\JokeApi\Model\Creator;

use App\Components\JokeApi\Exception\ApiErrorException;
use App\Components\JokeApi\Exception\InvalidResponseException;
use App\Components\JokeApi\Model\CategoriesResponseModel;
use App\Components\JokeApi\Model\Creator\Builder\ResponseModelBuilderInterface;
use App\Components\JokeApi\Model\Creator\ResponseModelCreator;
use App\Components\JokeApi\Model\ResponseModelInterface;
use App\Components\Services\Decoder\DecoderInterface;
use App\Components\Services\Validator\Object\ObjectValidatorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @coversDefaultClass \App\Components\JokeApi\Model\Creator\ResponseModelCreator
 */
class ResponseModelCreatorTest extends TestCase
{
    /**
     * @var DecoderInterface | MockObject
     */
    private $decoder;
    /**
     * @var ObjectValidatorInterface | MockObject
     */
    private $validator;
    /**
     * @var ResponseModelBuilderInterface | MockObject
     */
    private $modelBuilder;
    /**
     * @var ResponseInterface | MockObject
     */
    private $response;
    /**
     * @var ResponseModelInterface | MockObject
     */
    private $responseModel;

    /**
     * @var ResponseModelCreator
     */
    private $modelCreator;

    public function setUp(): void
    {
        parent::setUp();

        $this->decoder = $this->getMockBuilder(DecoderInterface::class)
            ->disableOriginalConstructor()->getMock();
        $this->validator = $this->getMockBuilder(ObjectValidatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->modelBuilder = $this->getMockBuilder(ResponseModelBuilderInterface::class)
            ->disableOriginalConstructor()->getMock();
        $this->response = $this->getMockBuilder(ResponseInterface::class)
            ->disableOriginalConstructor()->getMock();
        $this->responseModel = $this->getMockBuilder(ResponseModelInterface::class)
            ->disableOriginalConstructor()->getMock();

        $this->modelCreator = new ResponseModelCreator(
            $this->decoder,
            $this->validator,
            $this->modelBuilder
        );
    }

    /**
     * @covers ::<public>
     * @covers ::<private>
     */
    public function testCreateValidModel()
    {
        $modelClass = CategoriesResponseModel::class;
        $decodedBody = ['type' => 'success', 'value' => ['cat1']];

        $this->response->expects($this->once())->method('getStatusCode')->willReturn(Response::HTTP_OK);
        $this->response->expects($this->once())->method('getBody')->willReturn('encoded_string');

        $this->decoder->expects($this->once())->method('decodeToArray')->with('encoded_string')
            ->willReturn($decodedBody);

        $this->modelBuilder->expects($this->once())->method('build')
            ->willReturn($this->responseModel);

        $this->validator->expects($this->once())->method('isValid')->with($this->responseModel)->willReturn(true);

        $this->assertEquals($this->responseModel, $this->modelCreator->createValidModel($this->response, $modelClass));
    }

    /**
     * @covers ::<public>
     * @covers ::<private>
     */
    public function testCreateValidModelWithUnsuccessStatusCode()
    {
        $this->expectException(ApiErrorException::class);
        $modelClass = CategoriesResponseModel::class;

        $this->response->expects($this->exactly(2))->method('getStatusCode')->willReturn(Response::HTTP_INTERNAL_SERVER_ERROR);
        $this->response->expects($this->once())->method('getBody')->willReturn('error_string');

        $this->decoder->expects($this->never())->method('decodeToArray');
        $this->modelBuilder->expects($this->never())->method('build');

        $this->validator->expects($this->never())->method('isValid');

        $this->modelCreator->createValidModel($this->response, $modelClass);
    }

    /**
     * @covers ::<public>
     * @covers ::<private>
     */
    public function testCreateValidModelWithInvalidResponseException()
    {
        $this->expectException(InvalidResponseException::class);
        $modelClass = CategoriesResponseModel::class;
        $decodedBody = ['type' => 'success', 'value' => ['cat1']];

        $this->response->expects($this->once())->method('getStatusCode')->willReturn(Response::HTTP_OK);
        $this->response->expects($this->once())->method('getBody')->willReturn('encoded_string');

        $this->decoder->expects($this->once())->method('decodeToArray')->with('encoded_string')
            ->willReturn($decodedBody);

        $this->modelBuilder->expects($this->once())->method('build')
            ->willReturn($this->responseModel);

        $this->validator->expects($this->once())->method('isValid')->with($this->responseModel)->willReturn(false);

        $this->assertEquals($this->responseModel, $this->modelCreator->createValidModel($this->response, $modelClass));
    }
}