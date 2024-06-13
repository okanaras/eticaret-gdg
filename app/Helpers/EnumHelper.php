<?php

namespace App\Helpers;

use App\Enums\GenderEnum;

class EnumHelper
{
    public static function getGenders(GenderEnum $genderEnum): string
    {
        $configText = config('genders');

        return $configText[$genderEnum->value] ?? 'Bulunamadi';
    }
}