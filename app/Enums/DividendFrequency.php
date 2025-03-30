<?php

namespace App\Enums;

enum DividendFrequency: string
{
    use Enumerable;

    case MONTHLY = 'monthly';
    case QUARTERLY = 'quarterly';
    case BI_ANNUALLY = 'bi-annually';
    case ANNUALLY = 'annually';
}
