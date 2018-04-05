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
        $scales=Scale::all()->toarray();
        // $Question=Question::all()->groupBy('scaleid');
        $Question = DB::table('scales')
            ->leftjoin('questions','scales.id','=','questions.scaleid')
            ->select('scales.id', DB::raw('count(scaleid) as total'))
            ->groupBy('scales.id')
            ->get();
        foreach ($Question as $key => $value) {
            $scales[$key]["questions"]=$value->total;           
        }
        return $scales;
    }
    public function getOneData(Scale $scale){
        $dimensions=Dimension::where('scaleid',$scale->id)->get()->toarray();
        foreach ($dimensions as $key => $value) {
            $dimensionName = $value["name"];
            $questions= Question::where(['scaleid'=>$scale->id,'dimension'=>$dimensionName])->get()->toarray();
            $dimensions[$key]["questions"]=$questions;
        }
        $scale["dimensions"] = $dimensions;
        return $scale;
    }
    public function insert(Request $request){
        $input = $request->all();
        try {
            $scaleid = Scale::create(['name'=>$input["name"], 'level'=>$input["level"],'author'=>"假的"])->id;
            // $scaleid=1;
            @$dimensionsarr = explode("*",$input["DimensionInput"]);
            $dimensions = array();
            foreach ($dimensionsarr as $dkey => $dvalue) {
                if(!empty($dvalue))
                    Dimension::create(["name"=>$dvalue,"scaleid"=>$scaleid]);
                @$questionsarr = explode("*", $input["d".($dkey+1)]);
                $questions =array();
                foreach ($questionsarr as $qkey => $qvalue) {
                    if(!empty($qvalue))
                        Question::create(["description"=>$qvalue,"scaleid"=>$scaleid,"dimension"=>$dvalue]);
                }
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
        $scaleid = $scale->id;
        $input = $request->all();
        // print_r($input);
        $newname = $input["newData"]["name"];
        $oldname = Scale::find($scaleid)->name;
        //更改名稱及等第
        if($newname!=$oldname){
            Scale::find($scaleid)->update(["name"=>$input["newData"]["name"],"level"=>$input["newData"]["level"]]);
        }
        else{
            Scale::find($scaleid)->update(["level"=>$input["newData"]["level"]]);
        }
        //更改構面
        $olddimensions = $input["oldData"]["oDimensionInput"];
        $newdimensions = $input["newData"]["DimensionOInput"];
        @$adddimensions = $input["newData"]["DimensionInput"];
        $newdimensions = explode("*", $newdimensions);
        // print_r($newdimensions);
        foreach ($olddimensions as $key => $value) {
            if($newdimensions[$key])
                Dimension::find($value)->update(["name"=>$newdimensions[$key]]);
            else{
                unset($input["oldData"]["od".($key+1)]);
                Dimension::find($value)->delete();
            }
        }
        $olddimensionsL = count($olddimensions);
        $newdimensionsL = count($newdimensions);
        if(isset($adddimensions)){
            $adddimensions = explode("*", $adddimensions);
            foreach ($adddimensions as $key => $value) {
                Dimension::create(["name"=>$value,"scaleid"=>$scaleid]);
            }
        }
        //將多餘資料刪除
        unset($input["oldData"]["oDimensionInput"]);
        unset($input["newData"]["DimensionInput"]);
        unset($input["newData"]["DimensionOInput"]);
        unset($input["newData"]["name"]);
        unset($input["newData"]["level"]);
        //更改題目敘述
        $oldquestions = $input["oldData"];
        $newquestions = $input["newData"];
        foreach ($newquestions as $key => $value) {
            $newquestions[$key] = explode("*", $value);
        }
        foreach ($oldquestions as $key => $value) {
            foreach ($value as $qkey => $qvalue) {
                Question::find($qvalue)->update(["description"=>$newquestions[substr($key,1)][$qkey]]);
            }
        }

        // print_r($input);
        return \Response::json(['status' => 'ok', 'msg' => '修改成功']);
    }
    public function delete(Request $request,Scale $scale){
        $scale->delete();
        return \Response::json(['status' => 'ok', 'msg' => '刪除成功']);
    }
}