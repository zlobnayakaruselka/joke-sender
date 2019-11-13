<?php
namespace App\Components\Entity\Collection;

interface EntityCollectionInterface
{
    public function getEntityClassName(): string;

    public function addItem(object $entity);
}