<?php

namespace App\Services\FileSystem;

interface CsvFileServiceInterface
{
    public function saveRow(string $fileName, array $row): void;
}