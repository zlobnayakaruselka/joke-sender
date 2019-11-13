<?php

namespace tests\Unit\Components\Entity\Factory;

use App\Feature\Joke\Entity\Collection\JokeCategoryCollection;
use App\Feature\Joke\Entity\Factory\EntityFactory;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Feature\Joke\Entity\Factory\EntityFactory
 */
class EntityFactoryTest extends TestCase
{
    /**
     * @var EntityFactory
     */
    private $entityFactory;

    public function setUp(): void
    {
        parent::setUp();

        $this->entityFactory = new EntityFactory();
    }

    /**
     * @covers ::<public>
     */
    public function testCreateJokeCategoryEntity(): void
    {
        $category = 'nerd';
        $entity = $this->entityFactory->createJokeCategoryEntity($category);

        $this->assertEquals($category, $entity->getValue());
    }

    /**
     * @covers ::<public>
     */
    public function testCreateEntityCollection(): void
    {
        $data = ['cat1', 'cat2'];
        /** @var JokeCategoryCollectionTest $collection */
        $collection = $this->entityFactory->createEntityCollection($data, JokeCategoryCollection::class);
        $this->assertInstanceOf(JokeCategoryCollection::class, $collection);

        $this->assertEquals($data, $collection->getValuesArray());
    }

    /**
     * @covers ::<public>
     */
    public function testCreateJokeEntity(): void
    {
        $data = [
            'id' => 123,
            'joke' => 'chuck joke',
            'categories' => ['cat1', 'cat2'],
            'api_name' => 'boromir'
        ];
        $entity = $this->entityFactory->createJokeEntity($data);

        $this->assertEquals($data['id'], $entity->getId());
        $this->assertEquals($data['joke'], $entity->getJoke());
        $this->assertEquals($data['api_name'], $entity->getApiName());
        $this->assertInstanceOf(JokeCategoryCollection::class, $entity->getCategories());
        $this->assertEquals($data['categories'], $entity->getCategoriesArray());
    }
}