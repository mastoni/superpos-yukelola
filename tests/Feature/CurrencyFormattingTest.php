<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CurrencyFormattingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        forget_app_currency();

        // Ensure IDR currency exists with Indonesian standard configuration
        DB::table('currencies')->updateOrInsert(
            ['code' => 'IDR'],
            [
                'name' => 'Indonesian Rupiah',
                'symbol' => 'Rp',
                'decimals' => 0,
                'symbol_first' => true,
                'thousands_separator' => '.',
                'decimal_separator' => ',',
                'is_active' => true,
            ]
        );

        // Ensure company base currency is IDR
        DB::table('company')->updateOrInsert(
            ['id' => 1],
            [
                'name' => 'YUKelola Test',
                'base_currency_code' => 'IDR',
            ]
        );

        forget_app_currency();
    }

    protected function tearDown(): void
    {
        forget_app_currency();
        parent::tearDown();
    }

    public function test_zero_is_formatted_as_rp_0(): void
    {
        $this->assertSame('Rp 0', format_money(0));
        $this->assertSame('Rp 0', format_money('0'));
        $this->assertSame('Rp 0', format_money(0.00));
        $this->assertSame('Rp 0', format_money(null));
    }

    public function test_small_numbers_are_formatted_with_space(): void
    {
        $this->assertSame('Rp 123', format_money(123));
        $this->assertSame('Rp 123', format_money('123'));
        $this->assertSame('Rp 1', format_money(1));
    }

    public function test_standard_rupiah_amount_is_formatted_as_rp_127_600(): void
    {
        $this->assertSame('Rp 127.600', format_money(127600));
        $this->assertSame('Rp 127.600', format_money('127600'));
        $this->assertSame('Rp 127.600', format_money(127600.00));
    }

    public function test_large_numbers_use_consistent_thousands_separators(): void
    {
        $this->assertSame('Rp 1.500.000', format_money(1500000));
        $this->assertSame('Rp 1.500.000.000', format_money(1500000000));
    }

    public function test_negative_numbers_are_formatted_properly(): void
    {
        $this->assertSame('-Rp 127.600', format_money(-127600));
        $this->assertSame('-Rp 500', format_money(-500));
    }

    public function test_format_money_range_helper(): void
    {
        $this->assertSame('Rp 10.000 – Rp 25.000', format_money_range(10000, 25000));
        $this->assertSame('Rp 10.000', format_money_range(10000, 10000));
    }

    public function test_non_idr_currencies_retain_their_canonical_spacing(): void
    {
        DB::table('currencies')->updateOrInsert(
            ['code' => 'USD'],
            [
                'name' => 'US Dollar',
                'symbol' => '$',
                'decimals' => 2,
                'symbol_first' => true,
                'thousands_separator' => ',',
                'decimal_separator' => '.',
                'is_active' => true,
            ]
        );

        $this->assertSame('$1,234.56', format_money(1234.56, null, 'USD'));
    }
}
