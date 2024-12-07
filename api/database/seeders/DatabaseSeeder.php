<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->count(10)->create([
            'name' => function () {
                return Str::random(10);
            },
            'email' => function () {
                return Str::random(10) . '@example.com'; // Generate a random email address
            },
            'email_verified_at' => now(),
            'password' => Hash::make('password')
        ]);
    }
}
