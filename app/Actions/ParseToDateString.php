<?php

namespace App\Actions;

use Carbon\Carbon;

class ParseToDateString
{
    public function execute($date): false|string
    {
        try {
            return Carbon::parse($date)->toDateString();
        } catch (\InvalidArgumentException) {
            return false;
        }
    }
}
