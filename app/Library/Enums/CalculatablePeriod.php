<?php

namespace App\Library\Enums;

enum CalculatablePeriod: string implements ShouldReturnValues
{
    use ReturnsValues;

    public const data = [
        "Hour" => "Hour",
        "Day" => "Day",
        "Week" => "Week",
        "Month" => "Month",
        "Quarter" => "Quarter",
        "Year" => "Year",
    ];

    case Hour = "Hour";
    case Day = "Day";
    case Week = "Week";
    case Month = "Month";
    case Year = "Year";
    case Quarter = "Quarter";
}
