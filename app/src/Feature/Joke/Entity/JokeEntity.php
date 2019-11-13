<?php
namespace App\Feature\Joke\Entity;

use App\Feature\Joke\Entity\Collection\JokeCategoryCollectionInterface;

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
     * @var JokeCategoryCollectionInterface
     */
    private $categories;
    /**
     * @var string
     */
    private $apiName;

    public function __construct(int $id, string $joke, JokeCategoryCollectionInterface $categories, string $apiName)
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
     * @return JokeCategoryCollectionInterface
     */
    public function getCategories(): JokeCategoryCollectionInterface
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

    /**
     * @return array
     */
    public function getCategoriesArray(): array
    {
        return $this->categories->getValuesArray();
    }
}