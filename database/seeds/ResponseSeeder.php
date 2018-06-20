<?php

use Illuminate\Database\Seeder;

class ResponseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Response::class, 5000)->create();
    }
}
