<?php
namespace App\Feature\Joke\Entity\Collection;

interface EntityCollectionInterface extends \IteratorAggregate
{
    public function getEntityClassName(): string;

    public function addItem(object $entity);

    public function getIterator(): \ArrayIterator;
}