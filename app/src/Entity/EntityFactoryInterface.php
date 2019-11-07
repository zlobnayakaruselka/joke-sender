<?php

namespace App\Entity;

interface EntityFactoryInterface
{
    public function createJokeEntity(int $id, string $joke, array $categories, string $apiName): JokeEntity;
}