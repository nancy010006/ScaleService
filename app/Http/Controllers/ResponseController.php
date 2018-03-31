<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Response;

class ResponseController extends Controller
{
    public function getData(Request $request){
        $Responses = Response::all();
        return $Responses;
    }
    public function getOneData(Request $request,Response $Response){
        return $Response;
    }
    public function insert(Request $request){
        try {
            $Response = Response::create($request->all());

        } catch (\Illuminate\Database\QueryException $e) {
            // dd($e);
            $error = $e->getCode();
            switch ($error) {
                default:
                    return \Response::json(['status' => 'error', 'msg' => '發生未預期錯誤，請聯絡管理人員','statuscode' => $error]);
                    break;
            }
        }
    	return \Response::json(['status' => 'ok', 'msg' => '新增成功']);
    }
    public function update(Request $request,Response $Response){
        $input = $request->all();
        $Response->update($input);
    	return \Response::json(['status' => 'ok', 'msg' => '修改成功']);
    }
    public function delete(Request $request,Response $Response){
        $Response->delete();
    	return \Response::json(['status' => 'ok', 'msg' => '刪除成功']);
    }
}
