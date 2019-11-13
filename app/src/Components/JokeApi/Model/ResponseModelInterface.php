<?php
namespace App\Components\JokeApi\Model;

interface ResponseModelInterface
{
    /**
     * @return string
     */
    public function getType();
    /**
     * @param string $type
     */
    public function setType($type);

    /**
     * @return array
     */
    public function getValue();

    /**
     * @return array $value
     */
    public function setValue($value);
}