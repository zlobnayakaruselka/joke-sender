<?php
namespace App\Components\JokeApi\Model;

use Symfony\Component\Validator\Constraints as Assert;

class JokeResponseModel implements ResponseModelInterface
{
    /**
     * @var string
     * @Assert\Choice({"success"})
     */
    private $type;
    /**
     * @var array
     * @Assert\Collection(
     *     fields = {
     *         "id" = @Assert\Min(limit = 1)
     *         "joke" = @Assert\Length(min = 1)
     *         "categories" = @Assert\All({
     *             @Assert\NotBlank,
     *             @Assert\Length(min=1)
     *          })
     *     },
     *     allowMissingFields = true
     * )
     */
    private $value;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return array
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param array $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}