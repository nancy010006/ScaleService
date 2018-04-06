<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(App\Response::class, function (Faker $faker) {
    $useridarr = DB::table('users')->pluck('id')->toarray();
    $qidarr = DB::table('scales')->pluck('id')->toarray();
    $userid = $faker->randomElement($useridarr);
    $scaleid = $faker->randomElement($qidarr);
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