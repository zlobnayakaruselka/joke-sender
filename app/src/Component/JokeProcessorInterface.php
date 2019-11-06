<?php
namespace App\Component;

use App\Services\JokeAPI\Exception\ApiErrorException;
use App\Services\JokeAPI\Exception\InvalidResponseException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

interface JokeProcessorInterface
{
    /**
     * @param string $category
     * @param string $email
     * @throws TransportExceptionInterface
     * @throws ApiErrorException
     * @throws InvalidResponseException
     */
    public function processJoke(string $category, string $email): void;
}