<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

    // $dimension = "";
$factory->define(App\Scale::class, function (Faker $faker) {
    $rand = rand(5,9);
    while($rand%2==0){
        $rand = rand(5,9);
    }
    return [
        'name' => $faker->company(),
        'level' => $rand,
        'author' => 'someone'
    ];
});
$factory->define(App\Question::class, function (Faker $faker) {
    $rand = rand(1,5);
    $dimension = DB::table('dimensions')->where('scaleid',$rand)->pluck('name')->toarray();
    return [
        'description' =>$faker->sentence(),
        'dimension' =>$faker->randomElement($dimension),
        'scaleid' =>$rand
    ];
});
$factory->define(App\Dimension::class, function (Faker $faker) {
    return [
        'name' =>$faker->word(),
        'scaleid' =>rand(1,5)
    ];
});