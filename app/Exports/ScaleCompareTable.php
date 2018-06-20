<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\CronbachAlpha;
use App\Dimension;
use App\Question;
use App\Scale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use collection;

class ScaleCompareTable implements WithTitle,FromCollection,ShouldAutoSize
{
    private $ScaleID;
    private $StartDate;
    private $EndDate;
    private $data;

    public function __construct(int $ScaleID,String $StartDate = null,String $EndDate = null)
    {
        $this->ScaleID  = $ScaleID;
        $scaleid = $ScaleID;
        //此問卷有哪些構面
        $dimensions = DB::table('questions')->select('dimensions.name')->join('dimensions', 'dimensions.id', '=', 'questions.dimension')->where('dimensions.scaleid', $scaleid)->groupBy('dimensions.name')->get()->toarray();
        $questions = DB::table('scales')->select('questions.description as qname', 'questions.id as qid', 'dimensions.name as dname')->join('dimensions', 'scales.id', '=', 'dimensions.scaleid')->join('questions', 'questions.dimension', '=', 'dimensions.id')->where('scales.id', $scaleid)->orderBy('questions.id')->get()->toarray();
        //comparsion qid對到各構面
        $comparison = array();
        $qidToDescriptionArr = array();
        foreach ($questions as $key => $value) {
            $comparison[$value->qid] = $value->dname . "*" . $value->qname;
            $qidToDescriptionArr[$value->qid] = $value->dname;
        }

        //構成MTMM矩陣
        $data = array();
        foreach ($comparison as $key => $value) {
            $data['question'][$value] = array();
        }

        // 限定時間
        if($StartDate&&$EndDate){
            $from = date($StartDate);
            $to = date($EndDate);
            $responses = DB::table('responses')->select('response')->where('scaleid', $scaleid)->whereBetween('created_at', [$from, $to])->get();
        }
        else
            $responses = DB::table('responses')->select('response')->where('scaleid', $scaleid)->get();

        foreach ($responses as $key => $value) {
            $tmp = jsonResponseTransfer($value->response);
            foreach ($tmp as $tkey => $tvalue) {
                array_push($data['question'][$comparison[$tkey]], $tvalue);
            }
        }
        $corr = array();

        ksort($data['question']);

        foreach ($data['question'] as $key => $value) {
            $dim = explode('*', $key)[0];
            $ques = explode('*', $key)[1];
            $corr[$dim][$ques] = array();
            $tmp = array();
            foreach ($data['question'] as $innerkey => $innervalue) {
                array_push($tmp, round(getcorr($value, $innervalue), 2));
            }
            $corr[$dim][$ques] = $tmp;
        }

        $result = array();
        
        //題目對照表
        foreach ($corr as $dimension => $questionAndValue) {
        	$count = 1;
        	foreach ($questionAndValue as $question => $value) {
        		array_push($result, [$dimension.$count++,$question]);
        	}
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
        return "題目對照表";
    }
}