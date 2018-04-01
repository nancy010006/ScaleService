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
        factory(App\Scale::class, 10)->create();
        factory(App\Dimension::class, 25)->create();
        factory(App\Question::class, 40)->create();
    }
}
