<?php

declare(strict_types=1);

namespace App\Actions;

final class SplitString
{
    /**
     * Splits a string by the given separator and returns an array of non-empty, trimmed elements.
     *
     * @param string $string
     * @param string $separator
     *
     * @return array<int, string>
     */
    public static function handle(string $string, string $separator = ','): array
    {
        return array_values(
            array_filter(
                array_map('trim', explode($separator, $string)),
                fn($item) => $item !== ''
            )
        );
    }
}
