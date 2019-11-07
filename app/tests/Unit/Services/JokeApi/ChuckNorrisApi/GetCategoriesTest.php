<?php
namespace tests\Unit\Services\JokeApi;

use App\Entity\EntityFactoryInterface;
use App\Services\HttpClient\HttpClientInterface;
use App\Services\JokeAPI\ChuckNorrisAPI;
use App\Services\JokeAPI\Exception\ApiErrorException;
use App\Services\JokeAPI\Exception\InvalidResponseException;
use App\Services\Validator\JsonSchemaValidatorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @coversDefaultClass \App\Services\JokeAPI\ChuckNorrisAPI
 */
class GetCategoriesTest extends TestCase
{
    /**
     * @var MockObject | HttpClientInterface
     */
    private $httpClient;
    /**
     * @var MockObject | JsonSchemaValidatorInterface
     */
    private $jsonSchemaValidator;
    /**
     * @var MockObject | ResponseInterface
     */
    private $response;
    /**
     * @var ChuckNorrisAPI
     */
    private $chuckNorrisApi;

    public function setUp(): void
    {
        parent::setUp();

        $this->httpClient = $this->getMockBuilder(HttpClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->jsonSchemaValidator = $this->getMockBuilder(JsonSchemaValidatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->response = $this->getMockBuilder(ResponseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $entityFactory = $this->getMockBuilder(EntityFactoryInterface::class)
            ->disableOriginalConstructor()->getMock();

        $this->chuckNorrisApi = new ChuckNorrisAPI($this->httpClient, $this->jsonSchemaValidator, $entityFactory);
    }

    /**
     * @covers ::getJokeCategories
     * @covers ::checkResponse
     */
    public function testGetJokeCategories(): void
    {
        $categories = ['type' => 'success', 'value' => ['explicit', 'nerdy']];
        
        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(Response::HTTP_OK);
        $this->response->expects($this->exactly(2))
            ->method('getBody')
            ->willReturn(json_encode($categories));

        $this->httpClient->expects($this->once())
            ->method('get')
            ->with('https://api.icndb.com/categories')
            ->willReturn($this->response);

        $this->jsonSchemaValidator->expects($this->once())
            ->method('isValid')
            ->with(
                json_encode($categories),
                realpath(__DIR__ . '/../../../../../src/Services/JokeAPI/ResponseJsonSchema/ChuckNorrisApi/categories_schema.json')
            )
            ->willReturn(true);

        $this->assertEquals($categories['value'], $this->chuckNorrisApi->getJokeCategories());
    }

    /**
     * @covers ::getJokeCategories
     */
    public function testGetJokeCategoriesWithRunTimeException(): void
    {
        $this->expectException(\RuntimeException::class);

        $this->httpClient->expects($this->once())
            ->method('get')
            ->willThrowException(new \RuntimeException());

        $this->jsonSchemaValidator->expects($this->never())
            ->method('isValid');
        $this->jsonSchemaValidator->expects($this->never())
            ->method('getErrors');

        $this->chuckNorrisApi->getJokeCategories();
    }

    /**
     * @covers ::getJokeCategories
     * @covers ::checkResponse
     */
    public function testGetJokeCategoriesWithApiErrorException(): void 
    {
        $this->expectException(ApiErrorException::class);

        $this->response = $this->getMockBuilder(ResponseInterface::class)->disableOriginalConstructor()->getMock();
        $this->response->expects($this->exactly(2))
            ->method('getStatusCode')
            ->willReturn(Response::HTTP_SERVICE_UNAVAILABLE);
        $this->response->expects($this->once())
            ->method('getBody')
            ->willReturn('api_error');

        $this->httpClient->expects($this->once())
            ->method('get')
            ->willReturn($this->response);

        $this->chuckNorrisApi->getJokeCategories();
    }

    /**
     * @covers ::getJokeCategories
     * @covers ::checkResponse
     */
    public function testGetJokeCategoriesWithInvalidResponse(): void
    {
        $this->expectException(InvalidResponseException::class);
        $categories = ['type' => 'success'];

        $this->response = $this->getMockBuilder(ResponseInterface::class)->disableOriginalConstructor()->getMock();
        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(Response::HTTP_OK);
        $this->response->expects($this->once())
            ->method('getBody')
            ->willReturn(json_encode($categories));

        $this->httpClient->expects($this->once())
            ->method('get')
            ->with('https://api.icndb.com/categories')
            ->willReturn($this->response);

        $this->jsonSchemaValidator->expects($this->once())
            ->method('isValid')
            ->willReturn(false);
        $this->jsonSchemaValidator->expects($this->once())
            ->method('getErrors')
            ->willReturn([]);

        $this->chuckNorrisApi->getJokeCategories();
    }
}