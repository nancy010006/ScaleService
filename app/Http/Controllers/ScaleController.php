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
        // $Question = DB::table('scales')
        //     ->leftjoin('questions','scales.id','=','questions.scaleid')
        //     ->select('scales.id', DB::raw('count(scaleid) as total'))
        //     ->groupBy('scales.id')
        //     ->get();
        // foreach ($Question as $key => $value) {
        //     $scales[$key]["questions"]=$value->total;           
        // }
        return $scales;
    }
    public function getOneData(Scale $scale){
        $dimensions=Dimension::where('scaleid',$scale->id)->get()->toarray();
        foreach ($dimensions as $key => $value) {
            $dimension = $value["id"];
            $questions= Question::where(['dimension'=>$dimension])->get()->toarray();
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
                    $Did = Dimension::create(["name"=>$dvalue,"scaleid"=>$scaleid])->id;
                @$questionsarr = explode("*", $input["d".($dkey+1)]);
                $questions =array();
                foreach ($questionsarr as $qkey => $qvalue) {
                    if(!empty($qvalue))
                        Question::create(["description"=>$qvalue,"dimension"=>$Did]);
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
            try {
                Scale::find($scaleid)->update(["name"=>$input["newData"]["name"],"level"=>$input["newData"]["level"]]);
            } catch (\Illuminate\Database\QueryException $e) {
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
        }
        else{
            Scale::find($scaleid)->update(["level"=>$input["newData"]["level"]]);
        }
        //更改構面
        if(array_key_exists("oldData",$input)){
            $olddimensions = $input["oldData"]["oDimensionInput"];
        }else{
            $olddimensions=array();
        }
        if(array_key_exists("DimensionOInput",$input["newData"])){
            $newdimensions = $input["newData"]["DimensionOInput"];
            $newdimensions = explode("*", $newdimensions);
        }else{
            $newdimensions=array();
        }
        foreach ($olddimensions as $key => $value) {
            if($newdimensions[$key])
                Dimension::find($value)->update(["name"=>$newdimensions[$key]]);
            else{
                unset($input["oldData"]["od".($key+1)]);
                Dimension::find($value)->delete();
            }
        }
        //新增購面
        if(array_key_exists("DimensionInput",$input["newData"])){
            $adddimensions = $input["newData"]["DimensionInput"];
            $adddimensions = explode("*", $adddimensions);
        }else{
            $adddimensions=array();
        }
        if(!array_key_exists("oldData",$input)){
                $input["oldData"]=array();
        }
        if(array_key_exists("oDimensionInput",$input["oldData"])){
        }else{
            $input["oldData"]["oDimensionInput"]=array();
        }
        $oDimensionIndex = $input["oldData"]["oDimensionInput"];
        
        foreach ($adddimensions as $key => $value) {
            //新增構面時把id記下來 這樣才能同時新增題目
            $Did = Dimension::create(["name"=>$value,"scaleid"=>$scaleid])->id;
            
            array_push($oDimensionIndex, $Did);
        }
        //刪除多餘項目
        unset($input["oldData"]["oDimensionInput"]);
        unset($input["newData"]["DimensionInput"]);
        unset($input["newData"]["DimensionOInput"]);
        unset($input["newData"]["name"]);
        unset($input["newData"]["level"]);
        //更改題目敘述
        if(array_key_exists("oldData",$input)){
            $oldquestions = $input["oldData"];
        }else{
            $oldquestions=array();
        }
        if(array_key_exists("oldquestions",$input["newData"])){
            $newquestions = $input["newData"]["oldquestions"];
        }else{
            $newquestions = array();
        }

        foreach ($newquestions as $key => $value) {
            $newquestions[$key] = explode("*", $value);
        }
        foreach ($oldquestions as $key => $value) {
            foreach ($value as $qkey => $qvalue) {
                if($newquestions[$key][$qkey])
                    Question::find($qvalue)->update(["description"=>$newquestions[$key][$qkey]]);
                else
                    Question::find($qvalue)->delete();
            }
        }
        if(array_key_exists("newquestions",$input["newData"])){
            $addQuestions = $input["newData"]["newquestions"];
        }else{
            $addQuestions = array();
        }
        foreach ($addQuestions as $key => $value) {
            $addQuestions[$key] = explode("*", $value);
        }

        // print_r($oDimensionIndex);
        // print_r($addQuestions);
        foreach ($addQuestions as $key => $value) {
            foreach ($value as $vkey => $vvalue) {
                Question::create(["description"=>$vvalue,"dimension"=>$oDimensionIndex[(substr($key,1)-1)]]);
            }
        }
        // if(isset($addQuestions)){
        //     $addQuestions = explode("*", $addQuestions);
        //     foreach ($addQuestions as $key => $value) {
        //         Question::create(["description"=>$value,"scaleid"=>$scaleid]);
        //     }
        // }

        // print_r($input);
        return \Response::json(['status' => 'ok', 'msg' => '修改成功']);
    }
    public function delete(Request $request,Scale $scale){
        $scale->delete();
        return \Response::json(['status' => 'ok', 'msg' => '刪除成功']);
    }
}