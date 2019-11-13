<?php
namespace tests\Unit\Components\JokeApi;

use App\Feature\Joke\Entity\Collection\JokeCategoryCollectionInterface;
use App\Feature\Joke\Entity\Collection\JokeCategoryCollection;
use App\Feature\Joke\Entity\Factory\EntityFactoryInterface;
use App\Feature\Joke\Entity\JokeEntity;
use App\Components\JokeApi\ChuckNorrisJokeApi;
use App\Components\JokeApi\Model\CategoriesResponseModel;
use App\Components\JokeApi\Model\Creator\ResponseModelCreatorInterface;
use App\Components\JokeApi\Model\JokeResponseModel;
use App\Components\JokeApi\Model\ResponseModelInterface;
use GuzzleHttp\Client;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * @coversDefaultClass \App\Components\JokeApi\ChuckNorrisJokeApi
 */
class ChuckNorrisJokeApiTest extends TestCase
{
    private const API_NAME = 'chuck';
    /**
     * @var MockObject | Client
     */
    private $httpClient;
    /**
     * @var MockObject | ResponseInterface
     */
    private $response;
    /**
     * @var MockObject | ResponseModelCreatorInterface
     */
    private $modelCreator;
    /**
     * @var MockObject | ResponseModelInterface
     */
    private $responseModel;
    /**
     * @var MockObject | EntityFactoryInterface
     */
    private $entityFactory;
    /**
     * @var ChuckNorrisJokeApi
     */
    private $chuckNorrisApi;

    public function setUp(): void
    {
        parent::setUp();

        $this->httpClient = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()->setMethods(['get'])->getMock();
        $this->response = $this->getMockBuilder(ResponseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->entityFactory = $this->getMockBuilder(EntityFactoryInterface::class)
            ->disableOriginalConstructor()->getMock();
        $this->modelCreator = $this->getMockBuilder(ResponseModelCreatorInterface::class)
            ->disableOriginalConstructor()->getMock();
        $this->responseModel = $this->getMockBuilder(ResponseModelInterface::class)
            ->disableOriginalConstructor()->getMock();

        $this->chuckNorrisApi = new ChuckNorrisJokeApi(
            $this->httpClient,
            $this->entityFactory,
            $this->modelCreator,
            self::API_NAME
        );
    }

    /**
     * @covers ::<public>
     */
    public function testGetJokeCategories(): void
    {
        $this->httpClient->expects($this->once())->method('get')
            ->with('https://api.icndb.com/categories')->willReturn($this->response);

        $this->modelCreator->expects($this->once())->method('createValidModel')
            ->with($this->response, CategoriesResponseModel::class)
            ->willReturn($this->responseModel);

        $this->responseModel->expects($this->once())->method('getValue')
            ->willReturn(['some_data']);

        $jokeCategoriesCollection = $this->getMockBuilder(JokeCategoryCollectionInterface::class)
            ->disableOriginalConstructor()->getMock();
        $this->entityFactory->expects($this->once())->method('createEntityCollection')
            ->with(['some_data'], JokeCategoryCollection::class)
            ->willReturn($jokeCategoriesCollection);

        $this->assertSame($jokeCategoriesCollection, $this->chuckNorrisApi->getJokeCategories());
    }

    /**
     * @covers ::<public>
     */
    public function testGetJokeByCategory(): void
    {
        $this->httpClient->expects($this->once())->method('get')
            ->with('https://api.icndb.com/jokes/random', ['query' => 'limitTo=[nerdy]'])
            ->willReturn($this->response);

        $this->modelCreator->expects($this->once())->method('createValidModel')
            ->with($this->response, JokeResponseModel::class)
            ->willReturn($this->responseModel);

        $this->responseModel->expects($this->once())->method('getValue')->willReturn(['some_data']);

        $jokeEntity = $this->getMockBuilder(JokeEntity::class)->disableOriginalConstructor()->getMock();

        $this->entityFactory->expects($this->once())->method('createJokeEntity')
            ->with(['some_data', 'api_name' => 'chuck'])
            ->willReturn($jokeEntity);

        $this->assertEquals($jokeEntity, $this->chuckNorrisApi->getJokeByCategory('nerdy'));
    }
}