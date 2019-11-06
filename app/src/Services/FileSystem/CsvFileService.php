<?php

namespace App\Services\FileSystem;

class CsvFileService implements CsvFileServiceInterface
{
    public function saveRow(string $fileName, array $row): void
    {
        $fileSource = fopen($fileName, 'a');
        fputcsv($fileSource, $row);
        fclose($fileSource);
    }
}