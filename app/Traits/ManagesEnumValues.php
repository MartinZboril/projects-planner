<?php

namespace App\Traits;

use Illuminate\Support\Collection;

trait ManagesEnumValues
{
    public static function getNames(): Collection
    {
        return collect(self::cases())->pluck('name');
    }

    public static function getValues(): Collection
    {
        return collect(self::cases())->pluck('value');
    }

    public static function hasValue(string $value): bool
    {
        return self::getValues()->contains($value);
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}