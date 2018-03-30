<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Scale;

class ScaleController extends Controller
{
    public function index(){

    	$scales=Scale::all(); 
    	return view('scale.index',['scales' => $scales]);
    }
    public function insert(Request $request){
        try {
            $Scale = Scale::create($request->all());

        } catch (\Illuminate\Database\QueryException $e) {
            // dd($e);
            $error = $e->getCode();
            switch ($error) {
                case '23000':
                    return \Response::json(['status' => 'error', 'msg' => '量表名稱重複']);
                    break;
                default:
                    return \Response::json(['status' => 'error', 'msg' => '發生未預期錯誤，請聯絡管理人員','statuscode' => $error]);
                    break;
            }
        }
    	return \Response::json(['status' => 'ok', 'msg' => '新增成功']);
    }
    public function update(Request $request){
        // $todo = Todo::find($request->id);
        $input = $request->all();
        $scale = Scale::find($request->id)->update($input);
        // $scale = Scale::find($request->id);
        // $scale = Scale::where('name', $request->name)->update(
        //     ['dimension'=>$request->dimension,
        //     'level'=>$request->level]
        //     );
        // $scale = Scale::where('level', $request->level)->get();
        return response()->json($scale);
    }
    public function delete(Request $request){
        $scale = Scale::find($request->id)->delete();
        return response()->json($scale);
    }
}