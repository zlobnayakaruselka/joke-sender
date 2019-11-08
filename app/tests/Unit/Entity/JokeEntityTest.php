<?php

namespace tests\Unit\Entity;

use App\Entity\JokeEntity;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Entity\JokeEntity
 */
class JokeEntityTest extends TestCase
{
    public function dataGet(): array
    {
        return [
            [[
                'id' => 123,
                'joke' => 'jooooke',
                'categories' => ['cat1', 'cat2'],
                'apiName' => 'name'
            ]]
        ];
    }

    /**
     * @dataProvider dataGet
     * @covers ::<public>
     */
    public function testGet(array $data): void
    {
        $jokeEntity = new JokeEntity($data['id'], $data['joke'], $data['categories'], $data['apiName']);

        foreach ($data as $parameter => $value) {
            $methodName = 'get' . ucfirst($parameter);
            $this->assertEquals($jokeEntity->$methodName(), $data[$parameter]);
        }
    }
}