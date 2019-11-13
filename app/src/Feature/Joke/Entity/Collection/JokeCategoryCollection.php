<?php
namespace App\Feature\Joke\Entity\Collection;

use App\Feature\Joke\Entity\JokeCategoryEntity;

class JokeCategoryCollection implements JokeCategoryCollectionInterface
{
    /**
     * @var JokeCategoryEntity[]
     */
    private $collection = [];

    public function getEntityClassName(): string
    {
        $path = explode('\\', JokeCategoryEntity::class);
        return array_pop($path);
    }

    /**
     * @param JokeCategoryEntity $jokeCategoryEntity
     */
    public function addItem($jokeCategoryEntity): void
    {
        $this->collection[spl_object_hash($jokeCategoryEntity)] = $jokeCategoryEntity;
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->collection);
    }

    /**
     * @return string[]
     */
    public function getValuesArray(): array
    {
        $values = [];
        foreach ($this->collection as $jokeCategoryEntity) {
            $values[] = $jokeCategoryEntity->getValue();
        }

        return $values;
    }
}