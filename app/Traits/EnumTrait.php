<?php

namespace App\Traits;

trait EnumTrait
{
    public static function getValues($excepts = ['none']): array
    {
        $values = array_column(self::cases(), 'value');

        return array_diff($values, $excepts);
    }

    public static function has($value): bool
    {
        $values = self::getValues();

        return in_array($value, $values, true);
    }
}
