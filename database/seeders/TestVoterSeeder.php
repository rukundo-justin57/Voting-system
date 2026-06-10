<?php

namespace Database\Seeders;

use App\Models\Voter;
use Illuminate\Database\Seeder;

class TestVoterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates sample voters for testing the voting system.
     * Rwanda National ID Numbers follow the format: GAS-YYYY-NNNNN
     */
    public function run(): void
    {
        $voters = [
            ['name' => 'Didier NDAYISENGA', 'national_id' => 'GAS-2024-00001'],
            ['name' => 'Jeanne d\'Arc UWIMANA', 'national_id' => 'GAS-2024-00002'],
            ['name' => 'Pierre HABIMANA', 'national_id' => 'GAS-2024-00003'],
            ['name' => 'Alice MUKAKABERA', 'national_id' => 'GAS-2024-00004'],
            ['name' => 'Eric NIYONZIMA', 'national_id' => 'GAS-2024-00005'],
            ['name' => 'Florence MUKAMURARA', 'national_id' => 'GAS-2024-00006'],
            ['name' => 'Charles KAGABO', 'national_id' => 'GAS-2024-00007'],
            ['name' => 'Rose MUKANKUNDIYE', 'national_id' => 'GAS-2024-00008'],
            ['name' => 'Jean Pierre HAKIZIMANA', 'national_id' => 'GAS-2024-00009'],
            ['name' => 'Beatrice NYIRABAGARUKA', 'national_id' => 'GAS-2024-00010'],
        ];

        foreach ($voters as $voter) {
            Voter::create($voter);
        }

        $this->command->info('Test voters seeded successfully!');
        $this->command->info('Total: ' . count($voters) . ' voters created.');
    }
}
