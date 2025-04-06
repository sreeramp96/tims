<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;


class TimeSheetEntrySeeder extends Seeder
{
    public function run(): void
    {

        $faker = Faker::create();

        collect(range(1, 10))->each(function () use ($faker) {
            DB::table('time_sheet_entries')->insert([
                'project_id' => $faker->randomElement(range(1, 10)),
                'issue' => $faker->sentence,
                'comment' => $faker->text,
                'duration' => $faker->randomFloat(1, 0.5, 8),
                'date' => $faker->dateTimeBetween('-1 week', '+1 week')->format('Y-m-d'),
                'user_id' => $faker->randomElement([1, 3]),
            ]);
        });
    }
}
