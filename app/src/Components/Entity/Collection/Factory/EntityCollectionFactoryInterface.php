<?php
namespace App\Components\Entity\Collection\Factory;

use App\Components\Entity\Collection\EntityCollectionInterface;
use App\Components\Entity\Factory\EntityFactoryInterface;

interface EntityCollectionFactoryInterface
{
    public function createCollection(array $data, string $collectionClass): EntityCollectionInterface;
}