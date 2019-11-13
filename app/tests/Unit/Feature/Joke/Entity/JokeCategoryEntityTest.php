<?php

namespace tests\Unit\Components\Entity;

use App\Feature\Joke\Entity\JokeCategoryEntity;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Feature\Joke\Entity\JokeCategoryEntity
 */
class JokeCategoryEntityTest extends TestCase
{
    /**
     * @covers ::<public>
     */
    public function testGet(): void
    {
        $data = [
            'value' => 'cat1',
        ];

        $jokeEntity = new JokeCategoryEntity($data['value']);

        foreach ($data as $parameter => $value) {
            $methodName = 'get' . ucfirst($parameter);
            $this->assertEquals($jokeEntity->$methodName(), $data[$parameter]);
        }
    }
}