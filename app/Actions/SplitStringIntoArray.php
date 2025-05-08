<?php

namespace App\Actions;

class SplitStringIntoArray
{
    public function handle(string $string, string $separator = ','): array
    {
        return array_filter(explode($separator, $string));
    }
}
