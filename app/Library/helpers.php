<?php

use Filament\Support\RawJs;

if (!function_exists("moneyMask")) {
    function moneyMask()
    {
        return RawJs::make(<<<'JS'
            $money($input)
        JS);
    }
}


if (!function_exists("sanitizeMoney")) {
    function sanitizeMoney($money): float
    {
        return str()->of($money)->replace(",", "")->toString();
    }
}
