<?php

namespace App\Library\Enums;

trait ReturnsValues
{
    public static function values(): array
    {
        return collect(self::cases())
            ->map(fn (self $case) => $case->value)
            ->toArray();
    }

    public static function associativeValues(): array
    {
        return collect(self::cases())
            ->mapWithKeys(function (self $case) {
                return [
                    $case->value => str()->of($case->value)->title()
                ];
            })
            ->toArray();

    }
}
