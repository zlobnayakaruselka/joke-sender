<?php

namespace tests\Unit\Entity;

use App\Entity\EntityFactory;
use App\Entity\JokeEntity;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Entity\EntityFactory
 */
class EntityFactoryTest extends TestCase
{
    /**
     * @covers ::<public>
     */
    public function testGet(): void
    {
        $data = [
            'id' => 123,
            'joke' => 'jooooke',
            'categories' => ['cat1', 'cat2'],
            'apiName' => 'name'
        ];
        $factory = new EntityFactory();

        $entity = $factory->createJokeEntity(...array_values($data));

        foreach ($data as $parameter => $value) {
            $methodName = 'get' . ucfirst($parameter);
            $this->assertEquals($entity->$methodName(), $data[$parameter]);
        }
    }
}