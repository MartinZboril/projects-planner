<?php

namespace App\Enums;

use App\Traits\ManagesEnumValues;

enum ProjectStatusEnum: int
{
    use ManagesEnumValues;

    case active = 1;
    case finish = 2;
    case archive = 3;
}