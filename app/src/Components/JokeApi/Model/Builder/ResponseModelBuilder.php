<?php
namespace App\Components\JokeApi\Model\Builder;

use App\Components\JokeApi\Model\ResponseModelInterface;

class ResponseModelBuilder implements ResponseModelBuilderInterface
{
    public function build(ResponseModelInterface $model, $data): ResponseModelInterface
    {
        foreach ($data as $parameter => $value) {
            $setterName = 'set' . ucfirst($parameter);
            if (method_exists($model, $setterName)) {
                $model->$setterName($value);
            }
        }

        return  $model;
    }

}