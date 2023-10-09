<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Administrator',
            'email' => 'administrator@email.com',
            'username'=>'Admin',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'role' => 1,
            'department' => 'Admin',
            'office_id' => 1,
            'status' => 'active',
        ]);
        
        \App\Models\Office::factory()->create([
            'office_name' => 'Admin',
            'office_abbrev' => 'AD',
            'office_description' => 'Administrator Office',
            'office_head'=>'Administrator',
            'office_type' => 'Admin',
            'status' => 'active',
        ]);

        // \App\Models\User::factory(10)->create();

        
    }
}
