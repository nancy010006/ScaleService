<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Scale;

class ScaleController extends Controller
{
    public function index(){

    	// dd($scale); 
    	return view('scale.index',[]);
    }
    public function insert(Request $request){
    	//新增方法1
    	// $Scale = new Scale();
    	// $Scale->title = $request->title;
    	// $Scale->save();

    	//新增方法2
    	// return Scale::create([
     //        'title' => $request['title']
     //    ]);

    	//新增方法3
        try {
            $Scale = Scale::create($request->all());

        } catch (\Illuminate\Database\QueryException $e) {
            dd($e);
            // return $e->getCode();
            // return parent::render($request, $e);
            // return \Response::json(['status' => 'error', 'msg' => '量表名稱重複']);
        }
    	return \Response::json(['status' => 'ok', 'msg' => '新增成功','data' =>$request->all()]);
    }
    public function update(Request $request){
        // $todo = Todo::find($request->id);
        $scale = Scale::where('name', $request->name)->update(
            ['dimension'=>$request->dimension,
            'level'=>$request->level]
            );
        $scale = Scale::where('level', $request->level)->get();
        return response()->json($scale);

    }
}