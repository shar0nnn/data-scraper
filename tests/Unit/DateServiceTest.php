<?php

namespace Tests\Unit;

use App\Services\DateService;
use Carbon\Carbon;
use Tests\TestCase;

class DateServiceTest extends TestCase
{
    public function test_to_date_string_method_returns_date_string_when_date_is_valid_string()
    {
        $date = '2025-04-11';
        $result = DateService::toDateString($date);
        $this->assertEquals($date, $result);
    }

    public function test_to_date_string_method_returns_date_string_when_date_is_carbon_object()
    {
        $date = now();
        $result = DateService::toDateString($date);
        $this->assertEquals($date->toDateString(), $result);
    }

    public function test_to_date_string_method_returns_date_string_when_date_is_null()
    {
        $date = null;
        $result = DateService::toDateString($date);
        $this->assertEquals(Carbon::parse($date)->toDateString(), $result);
    }

    public function test_to_date_string_method_returns_null_when_date_is_invalid_string()
    {
        $date = 'invalid date';
        $result = DateService::toDateString($date);
        $this->assertEquals(null, $result);
    }

    public function test_to_dmy_method_returns_dmy_date_format_when_date_is_valid_string()
    {
        $date = '11.04.2025';
        $result = DateService::toDMY($date);
        $this->assertEquals($date, $result);
    }

    public function test_to_dmy_method_returns_dmy_date_format_when_date_is_carbon_object()
    {
        $date = now();
        $result = DateService::toDMY($date);
        $this->assertEquals($date->format(DateService::DMY_DATE_FORMAT), $result);
    }

    public function test_to_dmy_method_returns_dmy_date_format_when_date_is_null()
    {
        $date = null;
        $result = DateService::toDMY($date);
        $this->assertEquals(Carbon::parse($date)->format(DateService::DMY_DATE_FORMAT), $result);
    }

    public function test_to_dmy_method_returns_null_when_date_is_invalid_string()
    {
        $date = 'invalid date';
        $result = DateService::toDMY($date);
        $this->assertEquals(null, $result);
    }
}
