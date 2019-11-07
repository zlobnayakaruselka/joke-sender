<?php
namespace tests\Functional\Services\FileSystem;

use App\Services\FileSystem\CsvFileService;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Services\FileSystem\CsvFileService
 */
class CsvFileServiceTest extends TestCase
{
    /**
     * @var string
     */
    private $filePath;
    /**
     * @var CsvFileService
     */
    private $csvFileService;

    public function setUp(): void
    {
        parent::setUp();

        $this->filePath = sys_get_temp_dir() . '/test.csv';
        $this->csvFileService = new CsvFileService();
    }

    public function dataSave()
    {
        return [
            [
                [[123]]
            ],
            [
                [[123, 123456]]
            ],
        ];
    }

    /**
     * @dataProvider dataSave
     * @covers ::saveRow
     */
    public function testSave(array $data): void
    {
        foreach ($data as $row) {
            $this->csvFileService->saveRow($this->filePath, $row);
        }

        $fileSource = fopen($this->filePath, 'r');
        $fileContent = [];

        while (($row = fgetcsv($fileSource)) !== false) {
            $fileContent[] = $row;
        }
        unlink($this->filePath);

        $this->assertEquals($data, $fileContent);
    }
}