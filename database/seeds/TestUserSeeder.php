<?php

use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        DB::table('users')->insert([
            'name' => 'TestUser',
            'email' => 'test@gmail.com',
            'password' => Hash::make(123),
            'api_token' => str_random(60),
            'birthday' => $faker->date('Y-m-d','now'),
            'area' => $faker->cityPrefix,
            'sex' => $faker->title,
            'job' => $faker->jobTitle,
            'auth' => 0,
        ]);
        DB::table('users')->insert([
            'name' => 'TestUser',
            'email' => 'admin@gmail.com',
            'password' => Hash::make(123),
            'api_token' => str_random(60),
            'birthday' => $faker->date('Y-m-d','now'),
            'area' => $faker->cityPrefix,
            'sex' => $faker->title,
            'job' => $faker->jobTitle,
            'auth' => 2,
        ]);
    }
}
