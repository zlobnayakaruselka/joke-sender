<?php
namespace tests\Unit\Services\JokeSaver;

use App\Entity\JokeEntity;
use App\Services\FileSystem\CsvFileServiceInterface;
use App\Services\JokeSaver\CsvJokeSaver;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @coversDefaultClass \App\Services\JokeSaver\CsvJokeSaver
 */
class CsvJokeSaverTest extends TestCase
{
    /**
     * @var Filesystem | MockObject
     */
    private $fileSystem;
    /**
     * @var CsvFileServiceInterface | MockObject
     */
    private $csvFileService;
    /**
     * @var JokeEntity | MockObject
     */
    private $jokeEntity;
    /**
     * @var string
     */
    private $directoryPath;
    /**
     * @var string
     */
    private $fileName;
    /**
     * @var CsvJokeSaver
     */
    private $csvJokeSaver;

    public function setUp(): void
    {
        parent::setUp();

        $this->fileSystem = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()->onlyMethods(['exists', 'mkdir'])->getMock();
        $this->csvFileService = $this->getMockBuilder(CsvFileServiceInterface::class)
            ->disableOriginalConstructor()->getMock();
        $this->jokeEntity = $this->getMockBuilder(JokeEntity::class)
            ->disableOriginalConstructor()->getMock();

        $this->directoryPath = sys_get_temp_dir() . '/jokes';
        $this->fileName = $this->directoryPath . '/chuck_norris_api.csv';
        
        $this->csvJokeSaver = new CsvJokeSaver($this->fileSystem, $this->csvFileService);
    }

    public function dataSave()
    {
        return [
            [true, 0],
            [false, 1]
        ];
    }

    /**
     * @dataProvider dataSave
     * @covers ::save
     * @covers ::__construct
     * @covers ::prepareDirectory
     * @covers ::getDirectoryPath
     * @covers ::getFullFileName
     * @covers ::createRowForCsv
     */
    public function testSave(bool $isDirectoryExist, int $mkDirCount): void
    {
        $email = 'chuck@good.com';
        $this->fileSystem->expects($this->once())
            ->method('exists')
            ->with($this->directoryPath)
            ->willReturn($isDirectoryExist);

        $this->fileSystem->expects($this->exactly($mkDirCount))
            ->method('mkdir');

        $this->jokeEntity->expects($this->once())
            ->method('getApiName')
            ->willReturn('chuck_norris_api');
        $this->jokeEntity->expects($this->once())
            ->method('getId');
        $this->jokeEntity->expects($this->once())
            ->method('getJoke');
        $this->jokeEntity->expects($this->once())
            ->method('getCategories');


        $this->csvFileService->expects($this->once())
            ->method('saveRow');

        $this->csvJokeSaver->save($this->jokeEntity, $email);
    }
}