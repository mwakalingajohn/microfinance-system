<?php

namespace App\Library\Enums;

enum OrganisationType: string implements ShouldReturnValues
{
    use ReturnsValues;

    public const data = [
        "Group" => "Group",
        "Company" => "Company",
    ];

    case Group = "Group";
    case Company = "Company";

}
