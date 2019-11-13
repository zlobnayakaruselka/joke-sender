<?php
namespace App\Components\Services\Decoder;

interface DecoderInterface
{
    /**
     * @param mixed $data
     * @return array
     */
    public function decodeToArray($data): array;
}