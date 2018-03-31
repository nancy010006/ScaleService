<?php

use Faker\Generator as Faker;

$factory->define(App\Scale::class, function (Faker $faker) {
    return [
        'name' => $faker->company(),
        'dimension' =>rand(0,10),
        'level' => rand(3,7),
        'author' => 'someone'
    ];
});
$factory->define(App\Question::class, function (Faker $faker) {
    return [
        'description' =>$faker->sentence(),
        'scaleid' =>rand(1,5)
    ];
});