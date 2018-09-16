<?php

namespace Football\DataFixtures;

use RuntimeException;

trait GetDataTrait
{
    private function getData($fileName): array
    {
        $data = file_get_contents(
            __DIR__.DIRECTORY_SEPARATOR.'Data'.DIRECTORY_SEPARATOR.$fileName
        );

        if (!$data) {
            throw new RuntimeException('Data not found.');
        }

        return json_decode($data, true);
    }
}
