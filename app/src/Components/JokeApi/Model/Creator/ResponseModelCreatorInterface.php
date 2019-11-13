<?php
namespace App\Components\JokeApi\Model\Creator;

use App\Components\JokeApi\Model\ResponseModelInterface;
use Psr\Http\Message\ResponseInterface;

interface ResponseModelCreatorInterface
{
    public function createValidModel(ResponseInterface $response, string $modelClass): ResponseModelInterface;
}