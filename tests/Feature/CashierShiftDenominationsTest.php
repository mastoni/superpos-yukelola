<?php

namespace Tests\Feature;

use App\Models\Shift;
use App\Models\Store;
use App\Models\User;
use App\Support\Denominations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CashierShiftDenominationsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed', ['--class' => 'Database\Seeders\DatabaseSeeder', '--force' => true]);

        // Ensure base currency is IDR
        $company = DB::table('company')->first();
        if ($company) {
            DB::table('company')->where('id', $company->id)->update(['base_currency_code' => 'IDR']);
        } else {
            DB::table('company')->insert([
                'name' => 'YUKelola Mart',
                'base_currency_code' => 'IDR',
                'country_code' => 'ID',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        forget_app_currency();
    }

    private function createStore(): Store
    {
        return Store::first() ?? Store::create([
            'name' => 'YUKelola Mart - Cabang Utama',
            'code' => 'YK-01',
            'timezone' => 'Asia/Jakarta',
            'currency_code' => 'IDR',
            'enforce_shifts' => true,
            'is_active' => true,
            'is_default' => true,
        ]);
    }

    public function test_idr_denominations_are_descending_and_have_no_zero_value(): void
    {
        $denoms = Denominations::forCurrency('IDR');

        $expected = [100000, 50000, 20000, 10000, 5000, 2000, 1000, 500, 200, 100];
        $this->assertSame($expected, $denoms, 'IDR denominations must match expected descending sequence.');

        foreach ($denoms as $d) {
            $this->assertGreaterThan(0, $d, 'No denomination should be zero or negative.');
        }

        // Active currency resolution
        $this->assertSame($expected, Denominations::forActiveCurrency());
    }

    public function test_cashier_page_passes_idr_denominations_without_zero_value(): void
    {
        $store = $this->createStore();

        $cashier = User::factory()->create([
            'email' => 'kasir@yukelola.id',
            'is_super_admin' => true,
            'is_active' => true,
        ]);

        $response = $this->actingAs($cashier)
            ->withSession([
                'active_store_id' => $store->id,
            ])
            ->get(route('cashier.index'));

        $response->assertOk();
        $response->assertSee('100000');
        $response->assertSee('50000');
        $response->assertSee('500');
        $response->assertDontSee('"denominations":[500,200,100,50,20,10,5,2,1,0.5,0.1]');
    }

    public function test_open_shift_calculation_and_storage_with_example_denominations(): void
    {
        $store = $this->createStore();

        $cashier = User::factory()->create([
            'email' => 'kasir@yukelola.id',
            'is_super_admin' => true,
            'is_active' => true,
        ]);

        // Example: 2 x 50.000 + 3 x 10.000 + 4 x 500 = 132.000
        $inputDenominations = [
            '50000' => 2,
            '10000' => 3,
            '500' => 4,
        ];

        $calculatedTotal = (2 * 50000) + (3 * 10000) + (4 * 500); // 132000
        $this->assertEquals(132000, $calculatedTotal);

        $response = $this->actingAs($cashier)
            ->withSession([
                'active_store_id' => $store->id,
            ])
            ->post(route('cashier.shift.open'), [
                'opening_cash' => $calculatedTotal,
                'opening_denominations' => $inputDenominations,
                'notes' => 'Kas awal Rp132.000 via kalkulator pecahan',
            ]);

        $response->assertRedirect(route('cashier.index'));

        $shift = Shift::where('user_id', $cashier->id)->latest('id')->first();
        $this->assertNotNull($shift, 'Shift must be created successfully.');
        $this->assertEquals(132000, (float) $shift->opening_cash);
        $this->assertSame(Shift::STATUS_OPEN, $shift->status);

        $denoms = $shift->opening_denominations;
        $this->assertEquals(2, $denoms['50000']);
        $this->assertEquals(3, $denoms['10000']);
        $this->assertEquals(4, $denoms['500']);
    }

    public function test_open_shift_handles_empty_or_zero_counts_gracefully(): void
    {
        $store = $this->createStore();

        $cashier = User::factory()->create([
            'email' => 'kasir@yukelola.id',
            'is_super_admin' => true,
            'is_active' => true,
        ]);

        $response = $this->actingAs($cashier)
            ->withSession([
                'active_store_id' => $store->id,
            ])
            ->post(route('cashier.shift.open'), [
                'opening_cash' => 0,
                'opening_denominations' => [
                    '100000' => '',
                    '50000' => null,
                ],
            ]);

        $response->assertRedirect(route('cashier.index'));

        $shift = Shift::where('user_id', $cashier->id)->latest('id')->first();
        $this->assertNotNull($shift);
        $this->assertEquals(0, (float) $shift->opening_cash);
    }

    public function test_open_shift_rejects_negative_or_fractional_denominations_or_invalid_cash(): void
    {
        $store = $this->createStore();

        $cashier = User::factory()->create([
            'email' => 'kasir@yukelola.id',
            'is_super_admin' => true,
            'is_active' => true,
        ]);

        // 1. Negative cash rejected
        $responseNegativeCash = $this->actingAs($cashier)
            ->withSession(['active_store_id' => $store->id])
            ->post(route('cashier.shift.open'), [
                'opening_cash' => -10000,
            ]);
        $responseNegativeCash->assertSessionHasErrors('opening_cash');

        // 2. Negative count rejected
        $responseNegativeCount = $this->actingAs($cashier)
            ->withSession(['active_store_id' => $store->id])
            ->post(route('cashier.shift.open'), [
                'opening_cash' => 50000,
                'opening_denominations' => ['50000' => -2],
            ]);
        $responseNegativeCount->assertSessionHasErrors('opening_denominations.50000');

        // 3. Fractional count rejected
        $responseFractionalCount = $this->actingAs($cashier)
            ->withSession(['active_store_id' => $store->id])
            ->post(route('cashier.shift.open'), [
                'opening_cash' => 50000,
                'opening_denominations' => ['50000' => 2.5],
            ]);
        $responseFractionalCount->assertSessionHasErrors('opening_denominations.50000');
    }

    public function test_sell_without_shift_bypass_flag_available_for_privileged_users(): void
    {
        $store = $this->createStore();

        $admin = User::factory()->create([
            'email' => 'admin@yukelola.id',
            'is_super_admin' => true,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)
            ->withSession(['active_store_id' => $store->id])
            ->get(route('cashier.index'));

        $response->assertOk();
        $response->assertSee('Jual tanpa shift');
    }
}
