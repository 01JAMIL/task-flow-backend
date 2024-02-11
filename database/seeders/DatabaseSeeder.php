<?php

namespace Database\Seeders;

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
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'first_name' => 'Jamil',
            'last_name' => 'Ben Brahim',
            'username' => "01JAMIL",
            'phone' => '26847258',
            'bio' => 'I\'m a fullstack developer',
            'email' => 'jamil@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
    }
}
