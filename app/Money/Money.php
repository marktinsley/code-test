<?php

namespace App\Money;

class Money
{
    public static function toInt(float $value): int
    {
        return bcmul($value, 100, 2);
    }

    public static function toFloat(int $value): float
    {
        return bcdiv($value, 100, 2);
    }
}
