<?php
namespace App\Components\Services\Decoder;

use App\Components\Services\Decoder\Exception\DecodeErrorException;

class JsonDecoder implements DecoderInterface
{
    /**
     * @param string $data
     * @return array
     * @throws DecodeErrorException
     */
    public function decodeToArray($data): array
    {
        $arrayData = json_decode($data, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new DecodeErrorException('Failed to decode data. Structure invalid for JSON.');
        }

        return $arrayData;
    }
}