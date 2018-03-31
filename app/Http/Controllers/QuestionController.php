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
    public function update(Request $request,Question $Question){
        $input = $request->all();
        $Question->update($input);
    	return \Response::json(['status' => 'ok', 'msg' => '修改成功']);
    }
    public function delete(Request $request,Question $Question){
        $Question->delete();
    	return \Response::json(['status' => 'ok', 'msg' => '刪除成功']);
    }
}
