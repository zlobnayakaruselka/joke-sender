<?php
namespace tests\Unit\Application\FileSystem;

use App\Application\FileSystem\Saver\JokeSaver;
use App\Feature\Joke\Entity\JokeEntity;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @coversDefaultClass \App\Application\FileSystem\Saver\JokeSaver
 */
class JokeSaverTest extends TestCase
{
    /**
     * @var Filesystem | MockObject
     */
    private $fileSystem;
    /**
     * @var JokeSaver
     */
    private $saver;

    public function setUp(): void
    {
        parent::setUp();

        $this->fileSystem = $this->getMockBuilder(Filesystem::class)->disableOriginalConstructor()->getMock();
        $this->saver = new JokeSaver($this->fileSystem);
    }

    /**
     * @covers ::<public>
     * @covers ::<private>
     */
    public function testSave(): void
    {
        /** @var JokeEntity | MockObject $jokeEntity */
        $jokeEntity = $this->getMockBuilder(JokeEntity::class)->disableOriginalConstructor()->getMock();
        $jokeEntity->expects($this->once())->method('getApiName')->willReturn('api');
        $jokeEntity->expects($this->once())->method('getId')->willReturn(1);
        $jokeEntity->expects($this->once())->method('getJoke')->willReturn('joke');
        $jokeEntity->expects($this->once())->method('getCategoriesArray')->willReturn(['joke_cat']);

        $this->fileSystem->expects($this->once())->method('appendToFile');

        $this->saver->save($jokeEntity, 'ya@ru.ru');
    }
}