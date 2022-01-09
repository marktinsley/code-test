<?php

namespace Tests\Unit\Money;

use App\Money\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    /**
     * @dataProvider to_int_provider
     */
    public function test_to_int(float $value, int $expected)
    {
        $this->assertSame($expected, Money::toInt($value));
    }

    /**
     * @dataProvider to_float_provider
     */
    public function test_to_float(int $value, float $expected)
    {
        $this->assertSame($expected, Money::toFloat($value));
    }

    public function to_int_provider()
    {
        yield [
            100,
            10000
        ];

        yield [
            0,
            0
        ];

        yield [
            100.01,
            10001
        ];

        yield [
            9999.99,
            999999
        ];
    }

    public function to_float_provider()
    {
        yield [
            10000,
            100.00
        ];

        yield [
            0,
            0.00
        ];

        yield [
            10001,
            100.01
        ];

        yield [
            999999,
            9999.99
        ];
    }
}
