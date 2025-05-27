<?php

namespace Database\Seeders;

use App\Models\option;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $options = [
            ['name' => 'Company name', 'value' => 'CMHardware', 'type' => 'compony_name'],
            ['name' => 'pagination', 'value' => 10, 'type' => 'pagination'],
            ['name' => 'currency', 'value' => 'PHP', 'type' => 'currency'],
        ];

        foreach ($options as $option) {
            option::updateOrCreate(
                ['name' => $option['name']],
                ['type' => $option['type'],
                'value' => $option['value']]
            );
        }
        $this->command->info('Option seeder completed successfully!');
            

    }
}
