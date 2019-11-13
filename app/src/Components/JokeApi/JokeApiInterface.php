<?php

namespace App\Components\JokeApi;

use App\Feature\Joke\Entity\Collection\JokeCategoryCollectionInterface;
use App\Feature\Joke\Entity\JokeEntity;

interface JokeApiInterface
{
    public function getJokeCategories(): JokeCategoryCollectionInterface;

     public function getJokeByCategory(string $categoryName): JokeEntity;
}