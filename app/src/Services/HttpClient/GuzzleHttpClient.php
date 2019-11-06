<?php

namespace App\Services\HttpClient;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class GuzzleHttpClient implements  HttpClientInterface
{
    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $url
     * @param array|null $options
     * @return ResponseInterface
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function get(string $url, array $options = null): ResponseInterface
    {
        return $this->client->get($url, $options);
    }
}