<?php

namespace App\Traits;

trait FileManipulatorTrait
{
    public function createFile(string $path, string $dataFile): void
    {
        $fp = fopen($path, 'w');
        fwrite($fp, $dataFile);
        fclose($fp);
    }
}