<?php

namespace App\Services;

use Carbon\Carbon;

class DateService
{
    public const string DMY_DATE_FORMAT = 'd.m.Y';

    public static function toDateString(Carbon|null|string $date): ?string
    {
        if ($date instanceof Carbon) {
            return $date->toDateString();
        }

        try {
            return Carbon::parse($date)->toDateString();
        } catch (\Exception) {
            return null;
        }
    }

    public static function toDMY(Carbon|null|string $date): ?string
    {
        if ($date instanceof Carbon) {
            return $date->format(self::DMY_DATE_FORMAT);
        }

        try {
            return Carbon::parse($date)->format(self::DMY_DATE_FORMAT);
        } catch (\Exception) {
            return null;
        }
    }
}
