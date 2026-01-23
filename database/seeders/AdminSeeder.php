<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Superadmin utama
        User::firstOrCreate(
            ['email' => 'hafidhadirrohman@gmail.com'],
            [
                'name' => 'Hafid',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
        User::firstOrCreate(
            ['email' => 'af434613@gmail.com'],
            [
                'name' => 'superadmin',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
            ]
        );
        // Update existing user to superadmin if exists
        User::where('email', 'hafidhadirrohman@gmail.com')
            ->update(['role' => 'admin']);
    }
}
