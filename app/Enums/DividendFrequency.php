<?php

namespace App\Enums;

enum DividendFrequency: string
{
    use Enumerable;

    case MONTHLY = 'monthly';
    case QUARTERLY = 'quarterly';
    case TWICE_YEARLY = 'twice-yearly';
    case YEARLY = 'yearly';
}
