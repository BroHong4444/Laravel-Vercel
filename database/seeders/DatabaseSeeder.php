<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a single admin user
        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'password' => Hash::make('Admin@rp*123'), // bcrypt hash
            'is_admin' => 1,
            'created_at' => Carbon::now(),
        ]);
    }
}
