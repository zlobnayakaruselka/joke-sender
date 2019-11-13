<?php
namespace App\Components\Entity\Collection;

use App\Components\Entity\JokeCategoryEntity;

interface JokeCategoryCollectionInterface extends EntityCollectionInterface
{
    /**
     * @param JokeCategoryEntity $entity
     */
    public function addItem($entity): void;

    public function getValuesArray(): array;

    public function getValuesJson(): string;
}