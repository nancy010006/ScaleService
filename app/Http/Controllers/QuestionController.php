<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Question;

class QuestionController extends Controller
{
	public function getData(Request $request){
        $questions = Question::all();
        return $questions;
    }
    public function getOneData(Request $request,Question $Question){
        return $Question;
    }
    public function insert(Request $request){
        try {
            $Question = Question::create($request->all());

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
    public function update(Request $request){
        $data = $request->all();
        unset($data["_token"]);
        $scaleid = $data["scaleid"];
        unset($data["scaleid"]);
        // data為全部dimension資料
        // 將data拆成old及new 比較 若old和new長度一樣則逐項替換 new>old表示有新增題目 new<old為刪除題目
        foreach ($data as $key => $value) {
            $dimensionName = $key;
            //若old沒有資料會出錯 故此處理
            if(array_key_exists("oldquestions",$value)){
                $oldlength = count($value["oldquestions"]);
                $oldquestions = $value["oldquestions"];
            }else{
                $oldlength = 0;
                $oldquestions = array();
            }
            $newlength = count($value["newquestions"]);
            $newquestions = $value["newquestions"];
            // print_r($oldlength." ".$newlength."\n");
            if($oldlength==$newlength){
                foreach ($oldquestions as $index => $id) {
                    if(empty($newquestions[$index]))
                        Question::find($id)->delete();
                    else   
                        Question::find($id)->update(['description'=>$newquestions[$index]]);
                }
            }else if($oldlength<$newlength){
                foreach ($oldquestions as $index => $id) {
                    if(empty($newquestions[$index]))
                        Question::find($id)->delete();
                    else   
                        Question::find($id)->update(['description'=>$newquestions[$index]]);
                }
                for ($i=$oldlength; $i <$newlength ; $i++) { 
                    if(!empty($newquestions[$i])){
                        Question::create([
                            'description' => $newquestions[$i],
                            'scaleid' => $scaleid,
                            'dimension' => $key
                        ]);
                    }
                }
            }else{
                return \Response::json(['status' => 'error', 'msg' => '發生異常，請聯絡管理人員']);
            }
        }
    	return \Response::json(['status' => 'ok', 'msg' => '修改成功']);
    }
    public function delete(Request $request,Question $Question){
        $Question->delete();
    	return \Response::json(['status' => 'ok', 'msg' => '刪除成功']);
    }
}
