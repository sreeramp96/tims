<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        User::factory()->create([
            'name' => 'Sreeram P',
            'email' => 'sreeram@gmail.com',
        ]);
        $this->call(ProjectSeeder::class);
        $this->call([TimeSheetEntrySeeder::class,]);
    }
}
