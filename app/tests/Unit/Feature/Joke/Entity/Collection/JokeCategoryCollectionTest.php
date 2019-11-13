<?php
namespace tests\Unit\Components\Entity\Collection;

use App\Feature\Joke\Entity\Collection\JokeCategoryCollection;
use App\Feature\Joke\Entity\JokeCategoryEntity;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Feature\Joke\Entity\Collection\JokeCategoryCollection
 */
class JokeCategoryCollectionTest extends TestCase
{
    /**
     * @var JokeCategoryCollection
     */
    private $collection;

    public function setUp(): void
    {
        parent::setUp();

        $this->collection = new JokeCategoryCollection();
    }

    /**
     * @covers ::<public>
     */
    public function testGetEntityName()
    {
        $this->assertEquals('JokeCategoryEntity', $this->collection->getEntityClassName());
    }

    public function dataAddItem()
    {
        $category1 = new JokeCategoryEntity('cat1');
        $category2 = new JokeCategoryEntity('cat2');
        $category3 = new JokeCategoryEntity('cat1');

        return [
            [[], 0],
            [[$category1], 1],
            [[$category1, $category2], 2],
            [[$category1, $category3], 2],
            [[$category1, $category1, $category2], 2],
        ];
    }

    /**
     * @dataProvider dataAddItem
     * @covers ::<public>
     */
    public function testAddItem($items, $count)
    {
        /** @var JokeCategoryEntity $item */
        foreach ($items as $item) {
            $this->collection->addItem($item);
        }

        $collectionIterator = $this->collection->getIterator();
        foreach ($collectionIterator as $collectionItem) {
            $this->assertInstanceOf(JokeCategoryEntity::class, $collectionItem);
        }

        $this->assertEquals($count, $collectionIterator->count());
    }

    /**
     * @dataProvider dataAddItem
     * @covers ::<public>
     */
    public function testGetValuesArray($items, $count)
    {
        $categories = [];
        /** @var JokeCategoryEntity $item */
        foreach ($items as $item) {
            $this->collection->addItem($item);
            $categories[] = $item->getValue();
        }
        $categories = array_unique($categories);

        foreach ($categories as $category) {
            $this->assertContains($category, $this->collection->getValuesArray());
        }

        $this->assertCount($count, $this->collection->getValuesArray());
    }
}