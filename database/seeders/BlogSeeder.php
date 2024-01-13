<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = \Faker\Factory::create();

        // Insert 50 records
        for ($i = 1; $i <= 50; $i++) {
            DB::table('blogs')->insert([
                'title' => $faker->sentence,
                'description' => $faker->paragraph,
                'email' => $faker->email,
                'category' => $faker->randomElement(['technology', 'programming', 'database', 'networking', 'software development']),
                'favorite' => $faker->numberBetween(50, 100),
                'cover_img' => 'https://via.placeholder.com/800x400',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
