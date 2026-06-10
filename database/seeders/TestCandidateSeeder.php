<?php

namespace Database\Seeders;

use App\Models\Candidate;
use Illuminate\Database\Seeder;

class TestCandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates sample candidates for the Gasabo District election.
     * Include candidates for Mayor, Vice Mayor, and Council Member positions.
     */
    public function run(): void
    {
        $candidates = [
            // Mayor Candidates
            ['name' => 'Jean Baptiste HABYARIMANA', 'position' => 'Mayor', 'bio' => 'Former District Vice Mayor with 10 years of public service experience.'],
            ['name' => 'Alice UWIMANA', 'position' => 'Mayor', 'bio' => 'Civil engineer and community development advocate.'],
            ['name' => 'Patrick NSHIMIYIMANA', 'position' => 'Mayor', 'bio' => 'Business leader focused on economic development and job creation.'],

            // Vice Mayor Candidates
            ['name' => 'Marie Claire MUKAMANA', 'position' => 'Vice Mayor', 'bio' => 'Social affairs expert with experience in education and health sectors.'],
            ['name' => 'David HABIMANA', 'position' => 'Vice Mayor', 'bio' => 'Urban planning specialist focused on sustainable development.'],

            // Council Member Candidates
            ['name' => 'Emmanuel KAGABO', 'position' => 'Council Member', 'bio' => 'Community organizer representing Kimironko sector.'],
            ['name' => 'Chantal NYIRAMANA', 'position' => 'Council Member', 'bio' => 'Women rights activist and youth empowerment champion.'],
            ['name' => 'Olivier MUGABO', 'position' => 'Council Member', 'bio' => 'Teacher and education policy advocate.'],
            ['name' => 'Grace MUKAMAZIMPAKA', 'position' => 'Council Member', 'bio' => 'Healthcare professional focused on community well-being.'],
        ];

        foreach ($candidates as $candidate) {
            Candidate::create($candidate);
        }

        $this->command->info('Test candidates seeded successfully!');
    }
}
