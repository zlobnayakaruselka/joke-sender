<?php
namespace App\Components\JokeApi\Model\Builder;

use App\Components\JokeApi\Model\ResponseModelInterface;

interface ResponseModelBuilderInterface
{
    public function build(ResponseModelInterface $model, $data): ResponseModelInterface;
}