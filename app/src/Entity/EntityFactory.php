<?php

namespace App\Entity;

class EntityFactory implements EntityFactoryInterface
{
    public function createJokeEntity(int $id, string $joke, array $categories, string $apiName): JokeEntity
    {
        return new JokeEntity($id, $joke, $categories, $apiName);
    }
}