<?php
namespace App\Feature\Joke\Entity\Factory;

use App\Feature\Joke\Entity\Collection\EntityCollectionInterface;
use App\Feature\Joke\Entity\JokeCategoryEntity;
use App\Feature\Joke\Entity\JokeEntity;

interface EntityFactoryInterface
{
    public function createJokeEntity(array $data): JokeEntity;

    public function createJokeCategoryEntity(string $value): JokeCategoryEntity;

    public function createEntityCollection(array $data, string $collectionClass): EntityCollectionInterface;
}