<?php
namespace App\Entity;

class JokeEntity
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $joke;
    /**
     * @var string[]
     */
    private $categories;
    /**
     * @var string
     */
    private $apiName;

    public function __construct(int $id, string $joke, array $categories, string $apiName)
    {
        $this->id = $id;
        $this->joke = $joke;
        $this->apiName = $apiName;
        $this->categories = $categories;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getJoke(): string
    {
        return $this->joke;
    }

    /**
     * @return string[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @return string
     */
    public function getApiName(): string
    {
        return $this->apiName;
    }
}