<?php

namespace App\Console\Commands;

use App\Models\Holding;
use Illuminate\Console\Command;
use App\Enums\DividendFrequency;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class AddHoldings extends Command
{
    protected $signature = 'app:add-holdings';
    protected $description = 'Add holdings to the database';

    public function handle(): void
    {
        Holding::create([
            'ticker' => text('What is the ticker?'),
            'units' => text('How many units?'),
            'price' => text('What was the purchase price?'),
            'yield' => text('What is the yield percentage?'),
            'dividend_frequency' => select(label: 'What is the dividend frequency?', options: DividendFrequency::select()),
            'drp_weight' => text('What is the DRP weight percentage?'),
        ]);
    }
}
