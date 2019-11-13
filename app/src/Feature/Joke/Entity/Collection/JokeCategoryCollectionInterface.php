<?php
namespace App\Feature\Joke\Entity\Collection;

use App\Feature\Joke\Entity\JokeCategoryEntity;

interface JokeCategoryCollectionInterface extends EntityCollectionInterface
{
    /**
     * @param JokeCategoryEntity $entity
     */
    public function addItem($entity): void;

    public function getValuesArray(): array;
}