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
}
