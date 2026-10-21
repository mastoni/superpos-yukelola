<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class UpdateToYukelolaSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();
        if ($company) {
            $company->update([
                'name' => 'YUKelola',
                'app_name' => 'YUKelola',
                'website' => 'https://yukelola.id',
            ]);
        }

        User::where('email', 'admin@superpos.test')->update([
            'name' => 'Admin YUKelola',
            'email' => 'admin@yukelola.id',
        ]);

        User::where('email', 'kasir@superpos.test')->update([
            'name' => 'Kasir YUKelola',
            'email' => 'kasir@yukelola.id',
        ]);
    }
}
