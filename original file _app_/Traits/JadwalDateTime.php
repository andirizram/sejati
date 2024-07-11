<?php

namespace App\Traits;

use Carbon\Carbon;

trait JadwalDateTime
{
    public function parseTimeToLocal($value)
    {
        return Carbon::create($value)
            ->setTimezone(env('CLIENT_TIMEZONE', config('app.timezone')))
            ->format('H:i');
    }

    public function parseDateToLocal($value)
    {
        return Carbon::create($value)
            ->translatedFormat('l, d F Y');
    }

    public function parseDateToDay($value)
    {
        return Carbon::create($value)
            ->setTimezone(env('CLIENT_TIMEZONE', config('app.timezone')))
            ->translatedFormat('l');
    }

    protected function parseExcelTime($value)
    {
        $UNIX_DATE = ($value - 25569) * 86400;
        $date_column = gmdate("H:i", $UNIX_DATE);
        return $this->parseDateTime($date_column);
    }
}
