<?php
namespace App\Components\Entity\Factory;

use App\Components\Entity\JokeCategoryEntity;
use App\Components\Entity\JokeEntity;

interface EntityFactoryInterface
{
    public function createJokeEntity(array $data, string $apiName): JokeEntity;

    public function createJokeCategoryEntity(string $value): JokeCategoryEntity;
}