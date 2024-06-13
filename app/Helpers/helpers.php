<?php

use App\Enums\GenderEnum;

if (!function_exists('getGender')) {
    function getGender(GenderEnum $genderEnum): string
    {
        $configText = config('genders');

        return $configText[$genderEnum->value] ?? 'Bulunamadi';
    }
}