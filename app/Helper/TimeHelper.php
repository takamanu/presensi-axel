<?php

namespace App\Helpers;

use Carbon\Carbon;

class TimeHelper
{
    public static function calculateTotalHours($jamMasuk, $jamKeluar)
    {
        $start = \Carbon\Carbon::parse($jamMasuk);
        $end = \Carbon\Carbon::parse($jamKeluar);
        return $start->diff($end)->format('%H Jam %I Menit');
    }

    public static function calculateLateHours($jamMasuk, $jamKerja)
    {
        $masuk = \Carbon\Carbon::parse($jamMasuk);
        $kerja = \Carbon\Carbon::parse($jamKerja);
        return $masuk > $kerja ? $masuk->diff($kerja)->format('%H Jam %I Menit') : 'On Time';
    }

    public static function calculateLateMinutes($actualTime, $expectedTime)
    {
        $actual = Carbon::createFromFormat('H:i:s', $actualTime);
        $expected = Carbon::createFromFormat('H:i:s', $expectedTime);

        if ($actual->greaterThan($expected)) {
            return $actual->diffInMinutes($expected); // Total minutes late
        }

        return 0; // No lateness
    }
}
