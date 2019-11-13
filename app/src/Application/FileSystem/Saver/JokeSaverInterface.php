<?php
namespace App\Application\FileSystem\Saver;

use App\Feature\Joke\Entity\JokeEntity;

interface JokeSaverInterface
{
    public function save(JokeEntity $jokeEntityEntity, string $email): void;
}