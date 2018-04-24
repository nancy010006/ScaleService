<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Scale;
use App\Response;
use App\Question;

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
    public function getSomeOneHistoryResponse(Request $request,Scale $Scale){
        $scaleid =  $Scale->id;
        $userid = $request->user()->id;
        $result = array();
        $temp = array();
        $dimensions = DB::table('questions')->select('dimensions.name')->join('dimensions','dimensions.id','=','questions.dimension')->where('dimensions.scaleid',$scaleid)->groupBy('dimensions.name')->get()->toarray();
        // print_r($dimensions);
        foreach ($dimensions as $key => $value) {
            $temp['score'][$value->name] = 0;
        }
        $questions = DB::table('scales')->select('dimensions.name as dname','questions.id as qid')->join('dimensions','scales.id','=','dimensions.scaleid')->join('questions','questions.dimension','=','dimensions.id')->where('scales.id',$scaleid)->orderBy('questions.id')->get()->toarray();
        $comparison = array();
        foreach ($questions as $key => $value) {
            $comparison[$value->qid] = $value->dname;
        }
        $questions = DB::table('scales')->select('dimensions.name as dname',DB::raw('count(*) as total'))->join('dimensions','scales.id','=','dimensions.scaleid')->join('questions','questions.dimension','=','dimensions.id')->groupBy('dimensions.name')->where('scales.id',$scaleid)->orderBy('questions.id')->get()->toarray();
        $questionNum = array();
        foreach ($questions as $key => $value) {
            $questionNum[$value->dname] = $value->total;
        }
        $sum = 0;
        foreach ($questionNum as $key => $value) {
            $sum+=$value;
        }
        $questionNum['total']=$sum;
        // print_r($questionNum);
        // print_r($comparison);
        $responses = Scale::select('responses.response','responses.created_at')->join('responses','responses.scaleid','=','scales.id')->where('responses.userid',$userid)->where('scales.id',$scaleid)->orderBy('responses.created_at')->get()->toarray();
        foreach ($responses as $key => $value) {
            $tmp = $temp;
            // print_r($tmp);
            $response = json_decode($value['response']);
            $responses[$key]['response'] = $response;
            // $tmp =$temp['dimensions'];
            foreach ($response as $rkey => $rvalue) {
                $tmp['score'][$comparison[$rvalue->qid]]+=$rvalue->val;
            }

            //總分
            $sum = 0;
            foreach ($tmp['score'] as $tkey => $tvalue) {
                $sum+=$tvalue;
            }
            $tmp['score']['total'] = $sum;

            //平均
            foreach ($tmp['score'] as $tkey => $tvalue) {
                $tmp['score'][$tkey] = round($tmp['score'][$tkey]/$questionNum[$tkey],2);
            }

            // print_r($value);
            $tmp['created_at'] = $value['created_at'];
            array_push($result, $tmp);
        }
        return \Response::json($result);
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
