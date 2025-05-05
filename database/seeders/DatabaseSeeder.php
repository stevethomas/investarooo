<?php

namespace Database\Seeders;

use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Holding;
use Illuminate\Database\Seeder;
use App\Enums\DividendFrequency;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@investaroo.test',
        ]);

        Holding::insert([
            [
                'ticker' => 'ATEC',
                'units' => 215,
                'purchase' => 29.203,
                'yield' => 0.82,
                'dividend_frequency' => DividendFrequency::YEARLY,
                'drp_weight' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ticker' => 'BTI',
                'units' => 21140,
                'purchase' => 1.223,
                'yield' => 5.5,
                'dividend_frequency' => DividendFrequency::TWICE_YEARLY,
                'drp_weight' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ticker' => 'MOT',
                'units' => 41223,
                'purchase' => 2.096,
                'yield' => 9.1,
                'dividend_frequency' => DividendFrequency::MONTHLY,
                'drp_weight' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ticker' => 'MRE',
                'units' => 10000,
                'purchase' => 2,
                'yield' => 4.8,
                'dividend_frequency' => DividendFrequency::MONTHLY,
                'drp_weight' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ticker' => 'MXT',
                'units' => 21567,
                'purchase' => 1.974,
                'yield' => 8.5,
                'dividend_frequency' => DividendFrequency::MONTHLY,
                'drp_weight' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ticker' => 'SNC',
                'units' => 32191,
                'price' => 0.801,
                'yield' => 6.9,
                'dividend_frequency' => DividendFrequency::MONTHLY,
                'drp_weight' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
