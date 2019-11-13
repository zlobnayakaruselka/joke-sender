<?php
namespace test\Unit\App\Components\Services\Decoder;

use App\Components\Services\Decoder\Exception\DecodeErrorException;
use App\Components\Services\Decoder\JsonDecoder;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Components\Services\Decoder\JsonDecoder
 */
class JsonDecoderTest extends TestCase
{
    /**
     * @covers ::<public>
     */
    public function testDecodeToArray()
    {
        $decoder = new JsonDecoder();

        $data = '{"type": "value"}';
        $decodedData = ['type' => 'value'];

        $this->assertEquals($decodedData, $decoder->decodeToArray($data));
    }

    /**
     * @covers ::<public>
     */
    public function testDecodeToArrayWithDecodeErrorException()
    {
        $this->expectException(DecodeErrorException::class);
        $decoder = new JsonDecoder();

        $data = '{"type": "value}';

        $decoder->decodeToArray($data);
    }
}