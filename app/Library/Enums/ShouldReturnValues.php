<?php

namespace App\Library\Enums;

interface ShouldReturnValues
{
    public static function values(): array;

    public static function associativeValues(): array;
}
