<?php
namespace App\Components\Entity\Collection;

use App\Components\Entity\JokeCategoryEntity;

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

    public function getValuesJson(): string
    {
        return json_encode($this->getValuesArray());
    }
}