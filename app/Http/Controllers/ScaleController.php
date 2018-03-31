<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Scale;
use App\Dimension;
use App\Question;

class ScaleController extends Controller
{
    public function index(){

    	return view('scale.index');
    }
    public function getData(){
        $scales=Scale::all(); 
        return $scales;
    }
    public function getOneData(Scale $scale){
        $Question=Question::where('scaleid',$scale->id)->get();
        $result=["scale"=>$scale,"Question"=>$Question];
        return $result;
    }
    public function insert(Request $request){
        try {
            $Scale = Scale::create($request->all());
            $arr = explode(',',$request->dimension);
            foreach ($arr as $key => $value) {
                $Dimension = Dimension::create(array('name' => $value,'scaleid' => $Scale->id));
            }
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
    public function update(Request $request,Scale $scale){
        $input = $request->all();
        $scale->update($input);
        return \Response::json(['status' => 'ok', 'msg' => '修改成功']);
    }
    public function delete(Request $request,Scale $scale){
        $scale->delete();
        return \Response::json(['status' => 'ok', 'msg' => '刪除成功']);
    }
}