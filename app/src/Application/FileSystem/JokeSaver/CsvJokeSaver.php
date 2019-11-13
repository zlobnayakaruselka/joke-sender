<?php
namespace App\Application\FileSystem\JokeSaver;

use App\Application\FileSystem\JokeSaver\Exception\InvalidDirectoryStructureException;
use App\Components\Entity\JokeEntity;
use Symfony\Component\Filesystem\Filesystem;

class CsvJokeSaver implements JokeSaverInterface
{
    protected const JOKES_DIR_NAME = 'jokes';

    /**
     * @var Filesystem
     */
    protected $fileSystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->fileSystem = $filesystem;
    }

    public function save(JokeEntity $jokeEntity, string $email):void
    {
        $this->checkDirectory();

        $fileSource = fopen($this->getFullFileName($jokeEntity->getApiName()), 'a');
        fputcsv($fileSource, $this->createRowForCsv($jokeEntity, $email));
        fclose($fileSource);
    }

    /**
     * @throws InvalidDirectoryStructureException
     */
    private function checkDirectory(): void
    {
        $directoryPath = $this->getDirectoryPath();
        if (!$this->fileSystem->exists($directoryPath)) {
            throw new InvalidDirectoryStructureException("Directory $directoryPath does not exist");
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
            $jokeEntity->getCategoriesAsJson(),
            $email,
            date('Y-m-d H:i:s'),
        ];
    }

}