<?php

namespace App\Enums;

use App\Traits\ManagesEnumValues;

enum RoleEnum: int
{
    use ManagesEnumValues;

    case boss = 1;
    case manager = 2;
    case employee = 3;
}
