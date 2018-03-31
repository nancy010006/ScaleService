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
$factory->define(App\Response::class, function (Faker $faker) {
    $useridarr = DB::table('users')->pluck('id')->toarray();
    $scaleidarr = DB::table('scales')->pluck('id')->toarray();
    $userid = $faker->randomElement($useridarr);
    $scaleid = $faker->randomElement($scaleidarr);
    $level = DB::table('scales')->where('id',$scaleid)->get();
    $level = $level[0]->level;
    $count = DB::table('questions')->where('scaleid',$scaleid)->count();
    $response ="";
    for ($i=0; $i < $count ; $i++) { 
        $response.=rand(1,$level).",";
    }
    $response = substr($response, 0,-1);
    return [
        'response' =>$response,
        'scaleid' =>$scaleid,
        'userid' =>$userid
    ];
});