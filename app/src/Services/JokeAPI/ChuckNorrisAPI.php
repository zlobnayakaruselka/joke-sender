<?php
namespace App\Services\JokeAPI;

use App\Entity\JokeEntity;
use App\Services\JokeAPI\Exception\ApiErrorException;
use App\Services\JokeAPI\Exception\InvalidResponseException;

class ChuckNorrisAPI extends JokeAPI
{
    protected const BASE_URL = 'https://api.icndb.com';
    protected const API_NAME = 'chuck_norris_api';

    protected const CATEGORIES_JSON_SCHEMA = '/ResponseJsonSchema/ChuckNorrisApi/categories_schema.json';
    protected const RANDOM_JOKE_JSON_SCHEMA = '/ResponseJsonSchema/ChuckNorrisApi/random_joke_schema.json';

    /**
     * @return array
     * @throws ApiErrorException
     * @throws InvalidResponseException
     */
    public function getJokeCategories(): array
    {
        $response = $this->httpClient->get(self::BASE_URL . '/categories');
        $this->checkResponse($response,self::CATEGORIES_JSON_SCHEMA);

        $responseBody = json_decode($response->getBody(), true);

        return $responseBody['value'];
    }

    /**
     * @param string $categoryName
     * @return JokeEntity
     * @throws InvalidResponseException
     * @throws ApiErrorException
     */
    public function getJokeByCategory(string $categoryName): JokeEntity
    {
        $response = $this->httpClient->get(
            self::BASE_URL . '/jokes/random',
            ['query' => "limitTo=[$categoryName]"]
        );

        $this->checkResponse($response, self::RANDOM_JOKE_JSON_SCHEMA);
        $responseBody = json_decode($response->getBody(), true);

        return $this->entityFactory->createJokeEntity(
            $responseBody['value']['id'],
            $responseBody['value']['joke'],
            $responseBody['value']['categories'],
            self::API_NAME
        );
    }
}