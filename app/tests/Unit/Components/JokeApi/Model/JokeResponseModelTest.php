<?php
namespace tests\Unit\Components\JokeApi\Model;

use App\Components\JokeApi\Model\JokeResponseModel;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Components\JokeApi\Model\JokeResponseModel
 */
class JokeResponseModelTest extends TestCase
{
    /**
     * @covers ::<public>
     */
    public function testGetSet(): void
    {
        $data = [
            'type' => 'success',
            'value' => ['tt', 'tttt']
        ];

        $model = new JokeResponseModel();

        foreach ($data as $parameter => $value) {
            $setterName = 'set' . ucfirst($parameter);
            $model->$setterName($value);

            $getterName = 'get' . ucfirst($parameter);
            $this->assertEquals($model->$getterName(), $data[$parameter]);
        }
    }
}