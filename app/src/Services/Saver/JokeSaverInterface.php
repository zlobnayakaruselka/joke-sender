<?php
namespace App\Services\Saver;

use App\Entity\JokeEntity;

interface JokeSaverInterface
{
    public function save(JokeEntity $jokeEntityEntity, string $email): void;
}