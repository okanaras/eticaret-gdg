<?php

namespace App\Enums;

enum DiscountTypeEnum: string
{
    case Percentage = 'percentage';
    case Amount = 'amount';
}