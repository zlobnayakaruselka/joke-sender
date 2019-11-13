<?php
namespace App\Components\JokeApi;

use App\Feature\Joke\Entity\Collection\JokeCategoryCollection;
use App\Feature\Joke\Entity\Collection\JokeCategoryCollectionInterface;
use App\Feature\Joke\Entity\Factory\EntityFactoryInterface;
use App\Feature\Joke\Entity\JokeEntity;
use App\Components\JokeApi\Model\Creator\ResponseModelCreatorInterface;
use App\Components\JokeApi\Model\CategoriesResponseModel;
use App\Components\JokeApi\Model\JokeResponseModel;
use GuzzleHttp\Client;

class ChuckNorrisJokeApi implements JokeApiInterface
{
    private const BASE_URL = 'https://api.icndb.com';

    /**
     * @var Client
     */
    private $httpClient;
    /**
     * @var EntityFactoryInterface
     */
    private $entityFactory;
    /**
     * @var ResponseModelCreatorInterface
     */
    private $modelCreator;
    /**
     * @var string
     */
    private $apiName;

    public function __construct(
        Client $httpClient,
        EntityFactoryInterface $entityFactory,
        ResponseModelCreatorInterface $modelCreator,
        string $apiName
    ) {
        $this->httpClient = $httpClient;
        $this->entityFactory = $entityFactory;
        $this->modelCreator = $modelCreator;
        $this->apiName = $apiName;
    }

    public function getJokeCategories(): JokeCategoryCollectionInterface
    {
        $response = $this->httpClient->get(self::BASE_URL . '/categories');
        $responseModel = $this->modelCreator->createValidModel($response, CategoriesResponseModel::class);

        /** @var JokeCategoryCollectionInterface $categories */
        $categories = $this->entityFactory->createEntityCollection(
            $responseModel->getValue(),
            JokeCategoryCollection::class
        );

        return $categories;
    }

    public function getJokeByCategory(string $categoryName): JokeEntity
    {
        $response = $this->httpClient->get(self::BASE_URL . '/jokes/random', ['query' => "limitTo=[$categoryName]"]);
        $responseModel = $this->modelCreator->createValidModel($response, JokeResponseModel::class);

        $jokeEntityData = $responseModel->getValue() + ['api_name' => $this->apiName];

        return $this->entityFactory->createJokeEntity($jokeEntityData);
    }
}