<?php

namespace App\Services\JokeAPI;

use App\Entity\EntityFactoryInterface;
use App\Entity\JokeEntity;
use App\Services\HttpClient\HttpClientInterface;
use App\Services\JokeAPI\Exception\ApiErrorException;
use App\Services\JokeAPI\Exception\InvalidResponseException;
use App\Services\Validator\JsonSchemaValidatorInterface;
use Psr\Http\Message\ResponseInterface;

abstract class JokeAPI
{
    protected const BASE_URL = '';
    protected const API_NAME = '';

    protected const CATEGORIES_JSON_SCHEMA = '';
    protected const RANDOM_JOKE_JSON_SCHEMA = '';

    /**
     * @var HttpClientInterface
     */
    protected $httpClient;
    /**
     * @var JsonSchemaValidatorInterface
     */
    protected $jsonSchemaValidator;
    /**
     * @var EntityFactoryInterface
     */
    protected $entityFactory;

    public function __construct(
        HttpClientInterface $httpClient,
        JsonSchemaValidatorInterface $jsonSchemaValidator,
        EntityFactoryInterface $entityFactory
    ) {
        $this->httpClient = $httpClient;
        $this->jsonSchemaValidator = $jsonSchemaValidator;
        $this->entityFactory = $entityFactory;
    }

    /**
     * @return array
     * @throws ApiErrorException
     * @throws InvalidResponseException
     */
    abstract public function getJokeCategories(): array;

    /**
     * @param string $categoryName
     * @return JokeEntity
     * @throws InvalidResponseException
     * @throws ApiErrorException
     */
    abstract public function getJokeByCategory(string $categoryName): JokeEntity;

    /**
     * @param ResponseInterface $response
     * @param string $jsonSchemaPath
     * @throws InvalidResponseException
     * @throws ApiErrorException
     */
    protected function checkResponse(ResponseInterface $response, string $jsonSchemaPath): void
    {
        if ($response->getStatusCode() !== 200) {
            throw new ApiErrorException($response->getStatusCode(), $response->getBody());
        }

        $responseBody = $response->getBody();
        if (!$this->jsonSchemaValidator->isValid($responseBody, realpath(__DIR__ . $jsonSchemaPath))) {
            throw new InvalidResponseException($this->jsonSchemaValidator->getErrors());
        }
    }
}