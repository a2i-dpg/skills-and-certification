<?php

if (!function_exists('getMonthList')) {
    function getMonthList($lang = 'en'): array
    {
        $en = [
            '1' => 'January',
            '2' => 'February',
            '3' => 'March',
            '4' => 'April',
            '5' => 'May',
            '6' => 'June',
            '7' => 'July',
            '8' => 'August',
            '9' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December'
        ];

        $bn = [
            '1' => 'জানুয়ারি',
            '2' => 'ফেব্রুয়ারি',
            '3' => 'মার্চ',
            '4' => 'এপ্রিল',
            '5' => 'মে',
            '6' => 'জুন',
            '7' => 'জুলাই',
            '8' => 'আগস্ট',
            '9' => 'সেপ্টেমবর',
            '10' => 'অক্টোবর',
            '11' => 'নভেম্বর',
            '12' => 'ডিসেম্বর'
        ];

        return $lang === 'bn' ? $bn : $en;
    }
}


