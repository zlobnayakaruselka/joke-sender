<?php
namespace App\Application\FileSystem\JokeSaver;

use App\Components\Entity\JokeEntity;

interface JokeSaverInterface
{
    public function save(JokeEntity $jokeEntityEntity, string $email): void;
}