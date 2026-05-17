<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UpdateAdminCredentialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        if ($admin) {
            $admin->update([
                'email' => 'deren@gmail.com',
                'password' => Hash::make('darent12345*'),
            ]);
            $this->command->info('Admin credentials updated successfully.');
        } else {
            $this->command->error('Admin user not found.');
        }
    }
}
