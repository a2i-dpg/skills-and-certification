<?php

namespace App\Helpers\Classes;

use App\Models\Institute;

class Helper
{
    public static function randomPassword($length, $onlyDigit = false): string
    {
        $alphabet = $onlyDigit ? '1234567890' : 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $length; $i++) {
            $n = random_int(0, $alphaLength);
            $pass[] = $alphabet[$n] == '0' ? '1' : $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public static function validInstituteSlug($slug): bool
    {
        if (!$slug) {
            return false;
        }
        $institute = Institute::where('slug', $slug)->first();
        return boolval($institute);
    }

    public static function getLocaleCurrency(int $number): string
    {
        $localeCurrency = config('settings.local_currency') ?? 'USD';
        $locale = config('settings.locale') ?? 'en-US';
        $formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($number, $localeCurrency);
    }

    public static function getLocaleCustomCurrency($number, $locale, $local_currency)
    {
        $localeCurrency = $local_currency ?? config('settings.local_currency'); //?? 'USD';
        $locale = $locale ?? config('settings.locale'); //?? 'en-US';
        $formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($number, $localeCurrency);
    }
}


