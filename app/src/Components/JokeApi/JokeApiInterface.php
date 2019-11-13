<?php

namespace App\Components\JokeApi;

use App\Components\Entity\Collection\JokeCategoryCollectionInterface;
use App\Components\Entity\JokeEntity;

interface JokeApiInterface
{
    public function getJokeCategories(): JokeCategoryCollectionInterface;

     public function getJokeByCategory(string $categoryName): JokeEntity;
}