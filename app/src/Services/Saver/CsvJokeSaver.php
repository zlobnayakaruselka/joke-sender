<?php
namespace App\Services\Saver;

use App\Entity\JokeEntity;
use App\Services\FileSystem\CsvFileServiceInterface;
use Symfony\Component\Filesystem\Filesystem;

class CsvJokeSaver implements JokeSaverInterface
{
    protected const JOKES_DIR_NAME = 'jokes';

    /**
     * @var Filesystem
     */
    protected $fileSystem;
    /**
     * @var CsvFileServiceInterface
     */
    protected $csvFileWriter;

    public function __construct(Filesystem $filesystem, CsvFileServiceInterface $csvFileWriter)
    {
        $this->fileSystem = $filesystem;
        $this->csvFileWriter = $csvFileWriter;
    }

    public function save(JokeEntity $jokeEntity, string $email):void
    {
        $this->prepareDirectory();

        $this->csvFileWriter->saveRow(
            $this->getFullFileName($jokeEntity->getApiName()),
            $this->createRowForCsv($jokeEntity, $email)
        );
    }

    private function prepareDirectory(): void
    {
        $directoryPath = $this->getDirectoryPath();
        if (!$this->fileSystem->exists($directoryPath)) {
            $this->fileSystem->mkdir($directoryPath);
        }
    }

    private function getFullFileName(string $fileName): string
    {
        return $this->getDirectoryPath() . '/' . $fileName . '.csv';
    }

    private function getDirectoryPath(): string
    {
        return sys_get_temp_dir() . '/' . self::JOKES_DIR_NAME;
    }

    private function createRowForCsv(JokeEntity $jokeEntity, $email): array
    {
        return [
            $jokeEntity->getId(),
            $jokeEntity->getJoke(),
            json_encode($jokeEntity->getCategories()),
            $email,
            date('Y-m-d H:i:s'),
        ];
    }

}