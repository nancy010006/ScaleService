<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(App\Response::class, function (Faker $faker) {
    $users = DB::table('users')->pluck('id')->toarray();
    $userid = $faker->randomElement($users);
    $scales = DB::table('scales')->pluck('id')->toarray();
    $count=0;
    while ($count==0) {
        $scaleid = $faker->randomElement($scales);
        $scale = DB::table('questions')->select('questions.id')->join('dimensions','questions.dimension','=','dimensions.id')->join('scales','dimensions.scaleid','=','scaleid')->where('scales.id',$scaleid)->where('dimensions.scaleid',$scaleid);
        $count = $scale->count();
    }
    $level = DB::table('scales')->where('id',$scaleid)->get();
    $level = $level[0]->level;
    $response ="[";
    foreach ($scale->get()->toarray() as $key => $value) {
        $response.='{"qid":'.$value->id.',';
        $response.='"val":'.rand(1,$level)."},";
    }
    $response = substr($response, 0,-1);
    $response .= ']';
    // print_r($response);
    return [
        'response' =>$response,
        'scaleid' =>$scaleid,
        'userid' =>$userid
    ];
});