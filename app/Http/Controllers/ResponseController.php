<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Scale;
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
    public function getSomeOneHistoryResponses(Request $request){
        $userid = $request->user()->id;
        $scales = Scale::select('scales.id','scales.name')->join('responses','responses.scaleid','=','scales.id')->where('responses.userid',$userid)->groupBy('scales.id','scales.name')->get()->toarray();
        foreach ($scales as $key => $value) {
            $scales[$key]["responses"]=array();
            $responses = Scale::select('responses.response','responses.created_at')->join('responses','responses.scaleid','=','scales.id')->where('responses.userid',$userid)->where('scales.id',$value['id'])->get()->count();
            $scales[$key]["responses"]=$responses;
        }
        return $scales;
        // return Response::orderBy('scaleid')->where('userid',$userid)->get();
    }
    // public function getSomeOneHistoryResponses(Request $request){
    //     $userid = $request->user()->id;
    //     $scales = Scale::select('scales.id','scales.name')->join('responses','responses.scaleid','=','scales.id')->where('responses.userid',$userid)->groupBy('scales.id','scales.name')->get()->toarray();
    //     foreach ($scales as $key => $value) {
    //         $scales[$key]["responses"]=array();
    //         $responses = Scale::select('responses.response','responses.created_at')->join('responses','responses.scaleid','=','scales.id')->where('responses.userid',$userid)->where('scales.id',$value['id'])->get()->toarray();
    //         $scales[$key]["responses"]=$responses;
    //     }
    //     return $scales;
    // }
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
