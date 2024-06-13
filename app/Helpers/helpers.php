<?php

use App\Enums\GenderEnum;

if (!function_exists('getGender')) {
    function getGender(GenderEnum $genderEnum): string
    {
        $configText = config('genders');

        return $configText[$genderEnum->value] ?? 'Bulunamadi';
    }
}

if (!function_exists('pathEditor')) {
    function pathEditor(string $path): string
    {
        $path = str_replace('storage', '', $path);

        return 'public' . $path;
    }
}