<?php
namespace App\Application\FileSystem\Saver;

use App\Feature\Joke\Entity\JokeEntity;
use Symfony\Component\Filesystem\Filesystem;

class JokeSaver implements JokeSaverInterface
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
        $this->fileSystem->appendToFile(
            $this->getFullFileName($jokeEntity->getApiName()),
            $this->formatStoredValue($jokeEntity, $email) . PHP_EOL
        );
    }

    private function getFullFileName(string $fileName): string
    {
        return sys_get_temp_dir() . '/' . self::JOKES_DIR_NAME . '/' . $fileName . '.csv';
    }

    private function formatStoredValue(JokeEntity $jokeEntity, $email): string
    {
        return json_encode([
            'id' => $jokeEntity->getId(),
            'joke' => $jokeEntity->getJoke(),
            'categories' => $jokeEntity->getCategoriesArray(),
            'email' => $email,
            'date' => date('Y-m-d H:i:s'),
        ]);
    }
}