<?php

namespace Database\Seeders;

use App\Models\Tax;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taxes = [
            ['name' => 'VAT', 'percentage' => 20,'description' => 'Value Added Tax'],
            ['name' => 'Income Tax', 'percentage' => 30, 'description' => 'Tax on income earned'],
            ['name' => 'Corporate Tax', 'percentage' => 25, 'description' => 'Tax on corporate profits'],
            ['name' => 'Capital Gains Tax', 'percentage' => 15, 'description' => 'Tax on profits from asset sales'],
            ['name' => 'Property Tax', 'percentage' => 1.5, 'description' => 'Tax on property ownership'],
            ['name' => 'Excise Duty', 'percentage' => 12, 'description' => 'Tax on specific goods and services'],
        ];

        foreach ($taxes as $tax) {
            Tax::create($tax);
        }
        
        $this->command->info('Tax seeder completed successfully!');
    }
}
