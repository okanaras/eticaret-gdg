<?php

use App\Enums\DiscountTypeEnum;
use App\Enums\GenderEnum;

if (!function_exists('getGender')) {
    function getGender(GenderEnum $genderEnum): string
    {
        $configText = config('genders');

        return $configText[$genderEnum->value] ?? 'Bulunamadi';
    }
}

if (!function_exists('getDiscountType')) {
    function getDiscountType(DiscountTypeEnum $discountTypeEnum): string
    {
        $configText = config('discount_types');

        return $configText[$discountTypeEnum->value] ?? 'Bulunamadi';
    }
}

if (!function_exists('getAllDiscountTypes')) {
    function getAllDiscountTypes(): array
    {
        $discountTypes = [];
        foreach (DiscountTypeEnum::cases() as $discountTypeEnum) {
            $discountTypes[$discountTypeEnum->value] = getDiscountType($discountTypeEnum);
        }

        return $discountTypes;
    }
}

if (!function_exists('pathEditor')) {
    function pathEditor(string $path): string
    {
        $path = str_replace('storage', '', $path);

        return 'public' . $path;
    }
}