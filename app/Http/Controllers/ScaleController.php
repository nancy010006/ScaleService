<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Scale;
use App\Dimension;

class ScaleController extends Controller
{
    public function index(){

    	return view('scale.index');
    }
    public function getData(){

        $scales=Scale::all(); 
        return $scales;
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
    public function update(Request $request){
        $input = $request->all();
        $scale = Scale::find($request->id)->update($input);
        return \Response::json(['status' => 'ok', 'msg' => '修改成功']);
    }
    public function delete(Request $request){
        $scale = Scale::find($request->id)->delete();
        return \Response::json(['status' => 'ok', 'msg' => '刪除成功']);
    }
}