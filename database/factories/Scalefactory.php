<?php

use Faker\Generator as Faker;

$factory->define(App\Scale::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(),
        'dimension' =>3,
        'level' => 1,
        'author' => 'someone'
    ];
});
