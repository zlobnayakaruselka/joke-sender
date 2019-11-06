<?php
namespace App\Services\HttpClient;

use Psr\Http\Message\ResponseInterface;

interface HttpClientInterface
{
    public function get(string $url, array $options = null): ResponseInterface;
}