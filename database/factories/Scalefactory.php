<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

    // $dimension = "";
$factory->define(App\Scale::class, function (Faker $faker) {
    return [
        'name' => $faker->company(),
        'level' => rand(3,7),
        'author' => 'someone'
    ];
});
$factory->define(App\Question::class, function (Faker $faker) {
	$dimension = DB::table('dimensions')->pluck('name');
    return [
        'description' =>$faker->sentence(),
        'dimension' =>$dimension[rand(0,24)],
        'scaleid' =>rand(1,5)
    ];
});
$factory->define(App\Dimension::class, function (Faker $faker) {
    return [
        'name' =>$faker->word(),
        'scaleid' =>rand(1,5)
    ];
});