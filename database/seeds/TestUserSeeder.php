<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => str_random(10),
            'email' => 'test@gmail.com',
            'password' => Hash::make(123),
            'api_token' => str_random(60),
        ]);
    }
}
