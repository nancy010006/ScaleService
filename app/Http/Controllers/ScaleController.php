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
        // DB::connection()->enableQueryLog();
        // $Question=Question::where('scaleid',$scale->id)->get()->toarray();
        $dimension=Dimension::where('scaleid',$scale->id)->get()->toarray();
        $questions = DB::table('Dimensions')
            ->leftjoin('questions','Dimensions.name','=','questions.dimension','and','Dimensions.scaleid','=','questions.scaleid')
            ->select('questions.id as id','Dimensions.name as Dname',DB::raw('questions.description as description'))
            ->where('Dimensions.scaleid',$scale->id)
            ->where('questions.scaleid',$scale->id)
            // ->groupBy('scales.id')
            ->get()
            ->toarray();
        foreach ($dimension as $key => $value) {
            $dimensionD = $value["name"];
            $dimension[$key]["questions"]=array();
            $dimension[$key]["questionsid"]=array();
            foreach ($questions as $qkey => $qvalue) {
                if($qvalue->Dname==$dimensionD){
                    array_push($dimension[$key]["questions"], $qvalue->description);
                    array_push($dimension[$key]["questionsid"], $qvalue->id);
                }
            }
        }
        $result=["scale"=>$scale,"Dimension"=>$dimension];

        
            // dd(DB::getQueryLog());
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