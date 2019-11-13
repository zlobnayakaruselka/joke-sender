<?php
namespace App\Components\JokeApi;

use App\Components\Entity\Collection\Factory\EntityCollectionFactoryInterface;
use App\Components\Entity\Collection\JokeCategoryCollection;
use App\Components\Entity\Collection\JokeCategoryCollectionInterface;
use App\Components\Entity\Factory\EntityFactoryInterface;
use App\Components\Entity\JokeEntity;
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
     * @var EntityCollectionFactoryInterface
     */
    private $entityCollectionFactory;
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
        EntityCollectionFactoryInterface $entityCollectionFactory,
        EntityFactoryInterface $entityFactory,
        ResponseModelCreatorInterface $modelCreator,
        string $apiName
    ) {
        $this->httpClient = $httpClient;
        $this->entityCollectionFactory = $entityCollectionFactory;
        $this->entityFactory = $entityFactory;
        $this->modelCreator = $modelCreator;
        $this->apiName = $apiName;
    }

    public function getJokeCategories(): JokeCategoryCollectionInterface
    {
        $response = $this->httpClient->get(self::BASE_URL . '/categories');
        $responseModel = $this->modelCreator->createValidModel($response, CategoriesResponseModel::class);

        /** @var JokeCategoryCollectionInterface $categories */
        $categories = $this->entityCollectionFactory->createCollection(
            $responseModel->getValue(),
            JokeCategoryCollection::class
        );

        return $categories;
    }

    public function getJokeByCategory(string $categoryName): JokeEntity
    {
        $response = $this->httpClient->get(self::BASE_URL . '/jokes/random', ['query' => "limitTo=[$categoryName]"]);
        $responseModel = $this->modelCreator->createValidModel($response, JokeResponseModel::class);

        return $this->entityFactory->createJokeEntity($responseModel->getValue(), $this->apiName);
    }
}