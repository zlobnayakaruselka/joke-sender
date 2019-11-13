<?php
namespace tests\Unit\Application\Form\Model;

use App\Application\Form\Model\JokeSenderModel;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Application\Form\Model\JokeSenderModel
 */
class JokeSenderModelTest extends TestCase
{
    /**
     * @covers ::<public>
     */
    public function testGetSet(): void
    {
        $data = [
            'email' => 'su@su.su',
            'category' => 'cat1',
        ];

        $model = new JokeSenderModel();

        foreach ($data as $parameter => $value) {
            $setterName = 'set' . ucfirst($parameter);
            $model->$setterName($value);

            $getterName = 'get' . ucfirst($parameter);
            $this->assertEquals($model->$getterName(), $data[$parameter]);
        }
    }
}