<?php
namespace App\Feature\Joke\UseCase\GetCategories;

use App\Components\JokeApi\JokeApiInterface;

class Interactor
{
    /**
     * @var JokeApiInterface
     */
    private $jokeApi;

    public function __construct(JokeApiInterface $jokeApi)
    {
        $this->jokeApi = $jokeApi;
    }

    public function getCategories(): array
    {
        return $this->jokeApi->getJokeCategories()->getValuesArray();
    }
}