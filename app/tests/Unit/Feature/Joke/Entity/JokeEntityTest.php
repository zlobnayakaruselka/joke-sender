<?php

namespace tests\Unit\Components\Entity;

use App\Feature\Joke\Entity\Collection\JokeCategoryCollection;
use App\Feature\Joke\Entity\JokeEntity;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Feature\Joke\Entity\JokeEntity
 */
class JokeEntityTest extends TestCase
{

    /**
     * @covers ::<public>
     */
    public function testGet(): void
    {
        $data = [
            'id' => 123,
            'joke' => 'jooooke',
            'categories' => new JokeCategoryCollection(),
            'apiName' => 'name'
        ];

        $jokeEntity = new JokeEntity($data['id'], $data['joke'], $data['categories'], $data['apiName']);

        foreach ($data as $parameter => $value) {
            $methodName = 'get' . ucfirst($parameter);
            $this->assertEquals($jokeEntity->$methodName(), $data[$parameter]);
        }
    }

    /**
     * @covers ::<public>
     */
    public function testGetCategoriesArray(): void
    {
        $jokeCategoryCollection = $this->getMockBuilder(JokeCategoryCollection::class)
            ->getMock();

        $jokeCategoryCollection->expects($this->once())
            ->method('getValuesArray')
            ->willReturn(['some_string']);

        $data = [
            'id' => 123,
            'joke' => 'jooooke',
            'categories' => $jokeCategoryCollection,
            'apiName' => 'name'
        ];

        $jokeEntity = new JokeEntity($data['id'], $data['joke'], $data['categories'], $data['apiName']);

        $this->assertEquals(['some_string'], $jokeEntity->getCategoriesArray());
    }
}