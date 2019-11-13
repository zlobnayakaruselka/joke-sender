<?php
namespace App\Components\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class JokeCategoryEntity
{
    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}