<?php
namespace App\Components\Entity\Factory;

use App\Components\Entity\Collection\JokeCategoryCollection;
use App\Components\Entity\JokeCategoryEntity;
use App\Components\Entity\JokeEntity;

class EntityFactory implements EntityFactoryInterface
{
    public function createJokeEntity(array $data, string $apiName): JokeEntity
    {
        // When using a collection factory there is a cyclic dependency of classes. Forced duplication
        $categoryCollection = new JokeCategoryCollection();
        foreach ($data['categories'] as $category) {
            $categoryCollection->addItem($this->createJokeCategoryEntity($category));
        }

        return new JokeEntity($data['id'], $data['joke'], $categoryCollection, $apiName);
    }

    public function createJokeCategoryEntity(string $value): JokeCategoryEntity
    {
        return new JokeCategoryEntity($value);
    }
}