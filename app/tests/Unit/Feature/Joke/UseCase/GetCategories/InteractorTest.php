<?php
namespace tests\Unit\Feature\Joke\UseCase\GetCategories;

use App\Feature\Joke\Entity\Collection\JokeCategoryCollectionInterface;
use App\Components\JokeApi\JokeApiInterface;
use App\Feature\Joke\UseCase\GetCategories\Interactor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Feature\Joke\UseCase\GetCategories\Interactor
 */
class InteractorTest extends TestCase
{
    /** @var Interactor */
    private $interactor;
    /**
     * @var JokeApiInterface | MockObject
     */
    private $jokeApi;


    public function setUp(): void
    {
        parent::setUp();

        $this->jokeApi = $this->getMockBuilder(JokeApiInterface::class)->disableOriginalConstructor()->getMock();

        $this->interactor = new Interactor($this->jokeApi);
    }

    /**
     * @covers ::<public>
     */
    public function testGetCategories()
    {
        $categories = ['cat1', 'cat2'];
        $categoryCollection = $this->getMockBuilder(JokeCategoryCollectionInterface::class)
            ->disableOriginalConstructor()->getMock();
        $categoryCollection->expects($this->once())->method('getValuesArray')->willReturn($categories);

        $this->jokeApi->expects($this->once())->method('getJokeCategories')->willReturn($categoryCollection);

        $this->assertEquals($categories, $this->interactor->getCategories());
    }
}