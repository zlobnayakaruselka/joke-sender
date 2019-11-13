<?php
namespace App\Feature\Joke\Entity\Factory;

use App\Feature\Joke\Entity\Collection\EntityCollectionInterface;
use App\Feature\Joke\Entity\Collection\JokeCategoryCollection;
use App\Feature\Joke\Entity\JokeCategoryEntity;
use App\Feature\Joke\Entity\JokeEntity;

class EntityFactory implements EntityFactoryInterface
{
    public function createJokeEntity(array $data): JokeEntity
    {
        /** @var JokeCategoryCollection $categoryCollection */
        $categoryCollection = $this->createEntityCollection($data['categories'], JokeCategoryCollection::class);

        return new JokeEntity($data['id'], $data['joke'], $categoryCollection, $data['api_name']);
    }

    public function createJokeCategoryEntity(string $value): JokeCategoryEntity
    {
        return new JokeCategoryEntity($value);
    }

    public function createEntityCollection(array $data, string $collectionClass): EntityCollectionInterface
    {
        /** @var EntityCollectionInterface $collection */
        $collection = new $collectionClass();
        $entityFactoryMethodName = 'create' . $collection->getEntityClassName();

        foreach ($data as $item) {
            $collection->addItem($this->$entityFactoryMethodName($item));
        }

        return $collection;
    }
}