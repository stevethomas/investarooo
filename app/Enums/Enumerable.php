<?php

namespace App\Enums;

use Illuminate\Support\Str;

trait Enumerable
{
    public static function toArray(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($item) => [$item->value => $item->label()])
            ->toArray();
    }

    public static function values(): array
    {
        return array_values(self::cases());
    }

    public function label(): string
    {
        return __(Str::of($this->value)
            ->replace('_', ' ')
            ->title()
            ->__toString());
    }

    public static function select(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($item) => [$item->value => $item->label()])
            ->toArray();
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->map(fn ($item) => ['id' => $item->value, 'name' => $item->label()])
            ->toArray();
    }
}
