<?php

namespace App\Helpers;

class AcCapacity
{
    /**
     * Get all AC capacity options with labels.
     */
    public static function all(): array
    {
        return [
            '0.5pk' => '½ PK',
            '0.75pk' => '¾ PK',
            '1pk' => '1 PK',
            '1.5pk' => '1½ PK',
            '2pk' => '2 PK',
            '2.5pk' => '2½ PK',
            '3pk' => '3 PK',
        ];
    }

    /**
     * Get the label for a specific capacity.
     */
    public static function label(string $capacity): string
    {
        return self::all()[$capacity] ?? $capacity . ' PK';
    }

    /**
     * Get all capacity keys.
     */
    public static function keys(): array
    {
        return array_keys(self::all());
    }
}
