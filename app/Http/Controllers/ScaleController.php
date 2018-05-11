<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Scale;
use App\Dimension;
use App\Question;
use App\CronbachAlpha;

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
    public function getAnalysis(Request $request,Scale $Scale){
        //取資料庫資料
        $scaleid = $Scale->id;
        //此問卷有哪些構面
        $dimensions = DB::table('questions')->select('dimensions.name')->join('dimensions','dimensions.id','=','questions.dimension')->where('dimensions.scaleid',$scaleid)->groupBy('dimensions.name')->get()->toarray();
        //comparsion qid對到各構面
        $questions = DB::table('scales')->select('questions.description as qname','questions.id as qid','dimensions.name as dname')->join('dimensions','scales.id','=','dimensions.scaleid')->join('questions','questions.dimension','=','dimensions.id')->where('scales.id',$scaleid)->orderBy('questions.id')->get()->toarray();
        $comparison = array();
        foreach ($questions as $key => $value) {
            $comparison[$value->qid] = $value->dname."*".$value->qname;
        }

        //cronbach alpha 使用之變數宣告 待會使用折半信度內的tmp資料計算
        $alphaarray = array();
        

        //構成MTMM矩陣
        $data = array();
        foreach ($comparison as $key => $value) {
            $data['question'][$value] = array();
        }
        $responses = DB::table('responses')->select('response')->where('scaleid',$scaleid)->get();
        foreach ($responses as $key => $value) {
            $tmp =jsonResponseTransfer($value->response);
            foreach ($tmp as $tkey => $tvalue) {
                array_push($data['question'][$comparison[$tkey]],$tvalue);
            }
        }
        $corr = array();
        ksort($data['question']);
        //構成矩陣同時順便計算各構面題目數量 dimcountArr為各構面題目數量
        $dimcountArr = array();
        $olddim = explode('*',key($data['question']))[0];
        $dimcount=0;
        foreach ($data['question'] as $key => $value) {
            $dim = explode('*', $key)[0];
            if($dim==$olddim)
                $dimcount++;
            else{
                $dimcountArr[$olddim] = $dimcount;
                $dimcount=1;
            }
            $olddim = $dim;
            $ques = explode('*', $key)[1];
            $corr[$dim][$ques]=array();
            $tmp=array();
            foreach ($data['question'] as $innerkey => $innervalue) {
                array_push($tmp, round(getcorr($value,$innervalue),2));
            }
            $corr[$dim][$ques]=$tmp;
        }
        $dimcountArr[$olddim] = $dimcount;
        //算折半信度
        $odd = array();
        $even = array();
        $comparison = array();
        foreach ($questions as $key => $value) {
            if($key%2==0)
                $comparison[$value->qid]="odd";
            else
                $comparison[$value->qid]="even";
        }
        foreach ($responses as $key => $value) {
            $tmp = jsonResponseTransfer($value->response);

            //算cronbach alpha
            // print_r($tmp);
            // print_r($tmp);
            array_push($alphaarray, $tmp);

            //end cronbach alpha

            $data = array();
            $data["odd"] = 0;
            $data["even"] = 0;
            $count = 0;
            //innerkey 題號
            //innervalue 分數
            foreach ($tmp as $innerkey => $innervalue) {
                $data[$comparison[$innerkey]]+=$innervalue;
                $count++;
            }
            $data["odd"]/=$count;
            $data["even"]/=$count;
            array_push($odd, $data["odd"]);
            array_push($even, $data["even"]);
        }
        //折半信度結果
        $halfReliablity = round(getcorr($odd,$even),4);
        //因為key值混亂 算不出cronbach alpha 重新設定key值
        foreach($alphaarray as $key => $val){
            $new_key=0;
            ksort($alphaarray[$key]);
            foreach ($alphaarray[$key] as $innerkey => $innervalue) {
                $alphaarray[$key][$new_key++] = $innervalue;
                // print_r($innerkey."to ->".$new_key."\n");
                unset($alphaarray[$key][$innerkey]);
            }
        }
        // print_r($alphaarray);
        //算cronbach alpha
        $ca=new CronbachAlpha();
        $ca->LoadData($alphaarray);
        $alpha=round($ca->CalculateCronbachAlpha(),4);
        // $alpha =1 ;

        return \Response::json(["halfReliablity"=>$halfReliablity,"alpha"=>$alpha,"corr"=>$corr]);
    }
}