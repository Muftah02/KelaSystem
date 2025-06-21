<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public static function toHijri($date)
    {
        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        // أسماء الأشهر الهجرية
        $hijriMonths = [
            1 => 'محرم',
            2 => 'صفر',
            3 => 'ربيع الأول',
            4 => 'ربيع الثاني',
            5 => 'جمادى الأولى',
            6 => 'جمادى الآخرة',
            7 => 'رجب',
            8 => 'شعبان',
            9 => 'رمضان',
            10 => 'شوال',
            11 => 'ذو القعدة',
            12 => 'ذو الحجة'
        ];

        // تحويل بسيط للتاريخ (تقريبي)
        $year = $date->year - 579;
        $month = $date->month;
        $day = $date->day;

        return $day . ' ' . $hijriMonths[$month] . ' ' . $year;
    }
} 