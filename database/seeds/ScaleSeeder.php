<?php

use Illuminate\Database\Seeder;

class ScaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Scale::class, 5)->create();
        factory(App\Question::class, 100)->create();
    }
}
