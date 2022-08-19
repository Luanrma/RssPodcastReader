<?php

namespace App\Traits;

trait StringTraits
{
    public function removeAllSpecialCharacaters(string $word): string
    {
        return preg_replace('/[^A-Za-z0-9\-]/', '', $word);
    }
}