<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates the default admin user for the election management system.
     *
     * IMPORTANT: Change these credentials immediately after first login!
     */
    public function run(): void
    {
        // Check if admin user already exists and update, otherwise create
        $admin = User::where('is_admin', true)->first();

        if ($admin) {
            $admin->update([
                'name' => 'RUKUNDO JUSTIN',
                'username' => 'RUKUNDO JUSTIN',
                'password' => 'justin123',
            ]);
            $this->command->info('Admin user updated:');
        } else {
            User::create([
                'name' => 'RUKUNDO JUSTIN',
                'username' => 'RUKUNDO JUSTIN',
                'email' => 'admin@gasabo.gov.rw',
                'password' => 'justin123',
                'is_admin' => true,
            ]);
            $this->command->info('Default admin user created:');
        }

        $this->command->info('  Username: RUKUNDO JUSTIN');
        $this->command->info('  Password: justin123');
        $this->command->warn('  CHANGE THESE CREDENTIALS AFTER FIRST LOGIN!');
    }
}
