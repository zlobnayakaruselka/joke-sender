<?php
namespace App\Components\Entity\Collection\Factory;

use App\Components\Entity\Collection\EntityCollectionInterface;
use App\Components\Entity\Factory\EntityFactoryInterface;

class EntityCollectionFactory implements EntityCollectionFactoryInterface
{
    /**
     * @var EntityFactoryInterface
     */
    private $entityFactory;

    public function __construct(EntityFactoryInterface $entityFactory)
    {
        $this->entityFactory = $entityFactory;
    }

    public function createCollection(array $data, string $collectionClass): EntityCollectionInterface
    {
        /** @var EntityCollectionInterface $collection */
        $collection = new $collectionClass();
        $entityFactoryMethodName = 'create' . $collection->getEntityClassName();

        foreach ($data as $category) {
            $collection->addItem($this->entityFactory->$entityFactoryMethodName($category));
        }

        return $collection;
    }
}