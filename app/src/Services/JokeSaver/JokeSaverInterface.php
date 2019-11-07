<?php
namespace App\Services\JokeSaver;

use App\Entity\JokeEntity;

interface JokeSaverInterface
{
    public function save(JokeEntity $jokeEntityEntity, string $email): void;
}