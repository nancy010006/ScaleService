<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\CronbachAlpha;
use App\Dimension;
use App\Question;
use App\Scale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use collection;

class OriginData implements WithTitle,FromCollection
{
    private $ScaleID;
    private $StartDate;
    private $EndDate;
    private $data;

    public function __construct(int $ScaleID,String $StartDate = null,String $EndDate = null)
    {
        //製作輸出檔案
        $result = array();

        $this->ScaleID  = $ScaleID;

        // 題目編號
        $dimensions = Dimension::where('scaleid', $ScaleID)->get()->toarray();
        foreach ($dimensions as $key => $value) {
            $dimension = $value["id"];
            $questions = Question::where(['dimension' => $dimension])->get()->toarray();
            $dimensions[$key]["questions"] = $questions;
        }
        $scale["dimensions"] = $dimensions;
        $Qid = array("填答者","填答日期");
        $comparsion = array();
        foreach ($scale["dimensions"] as $key => $dimensions) {
            $tmpid = 1;
            foreach ($dimensions["questions"] as $key => $question) {
                array_push($Qid, $dimensions["name"].$tmpid++);
                array_push($comparsion, $question["id"]);
            }
        }
        array_push($result, $Qid);
        
        // 限定時間
        if($StartDate&&$EndDate){
            $from = date($StartDate);
            $to = date($EndDate);
            $responses = DB::table('responses')->select('response','users.name','responses.created_at')->join('users','responses.userid','=','users.id')->where('scaleid', $ScaleID)->whereBetween('responses.created_at', [$from, $to])->orderBy('responses.created_at','asc')->orderBy('users.name')->get();
        }
        else
            $responses = DB::table('responses')->select('response','users.name','responses.created_at')->join('users','responses.userid','=','users.id')->where('scaleid', $ScaleID)->orderBy('responses.created_at','asc')->orderBy('users.name')->get();
        
        //空行
        array_push($result, " ");
        
        //原始資料
        foreach ($responses as $key => $value) {
            $tmp = jsonResponseTransfer($value->response);
            $name = $value->name;
            $time = $value->created_at;
            $oneResponse = array();
            array_push($oneResponse, $name);
            array_push($oneResponse, $time);
            foreach ($comparsion as $ck => $qid) {
                array_push($oneResponse, $tmp[$qid]);
            }
            array_push($result, $oneResponse);
            // foreach ($tmp as $tkey => $tvalue) {
            //     array_push($result, $tkey);
            // }
        }

        


        $this->data = collect($result);
    }

    public function collection()
    {
        return collect($this->data);
    }

    /**
     * @return Builder
     */

    /**
     * @return string
     */
    public function title(): string
    {
        return "原始資料";
    }
}