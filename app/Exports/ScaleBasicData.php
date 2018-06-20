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

class ScaleBasicData implements WithTitle,FromCollection
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
        $qidToDimensionArr = array();
        foreach ($questions as $key => $value) {
            $comparison[$value->qid] = $value->dname . "*" . $value->qname;
            $qidToDimensionArr[$value->qid] = $value->dname;
        }

        //cronbach alpha 使用之變數宣告 待會使用折半信度內的tmp資料計算
        $alphaarray = array();

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
        //構成矩陣同時順便計算各構面題目數量 dimcountArr為各構面題目數量
        $dimcountArr = array();
        $olddim = explode('*', key($data['question']))[0];
        $dimcount = 0;

        //將數字索引轉換為題目敘述 供下方收斂效度使用
        $indexToQuestionArr = array();
        foreach ($data['question'] as $key => $value) {
            $dim = explode('*', $key)[0];
            if ($dim == $olddim) {
                $dimcount++;
            } else {
                $dimcountArr[$olddim] = $dimcount;
                $dimcount = 1;
            }
            $olddim = $dim;
            $ques = explode('*', $key)[1];
            $corr[$dim][$ques] = array();
            $tmp = array();
            foreach ($data['question'] as $innerkey => $innervalue) {
                array_push($tmp, round(getcorr($value, $innervalue), 2));
            }
            array_push($indexToQuestionArr, $ques);
            $corr[$dim][$ques] = $tmp;
        }
        $dimcountArr[$olddim] = $dimcount;

        //算折半信度
        $odd = array();
        $even = array();
        $comparison = array();
        foreach ($questions as $key => $value) {
            if ($key % 2 == 0) {
                $comparison[$value->qid] = "odd";
            } else {
                $comparison[$value->qid] = "even";
            }

        }
        foreach ($responses as $key => $value) {
            $tmp = jsonResponseTransfer($value->response);

            //cronbach alpha用
            array_push($alphaarray, $tmp);

            //end cronbach alpha

            $data = array();
            $data["odd"] = 0;
            $data["even"] = 0;
            $count = 0;
            //innerkey 題號
            //innervalue 分數
            foreach ($tmp as $innerkey => $innervalue) {
                $data[$comparison[$innerkey]] += $innervalue;
                $count++;
            }
            $data["odd"] /= $count;
            $data["even"] /= $count;
            array_push($odd, $data["odd"]);
            array_push($even, $data["even"]);
        }
        //折半信度結果
        $halfReliablity = round(getcorr($odd, $even), 4);

        //組各構面alpha準備計算資料
        $dimensionAlpha = array();
        foreach ($dimensions as $key => $value) {
            $dimensionAlpha[$value->name] = array();
        }
        foreach ($alphaarray as $key => $val) {
            $tmp = array();
            foreach ($dimensions as $key => $value) {
                $tmp[$value->name] = array();
            }
            foreach ($val as $qid => $score) {
                # code...
                // print_r($qidToDimensionArr[$qid]);
                array_push($tmp[$qidToDimensionArr[$qid]], $score);
            }
            foreach ($tmp as $key => $value) {
                array_push($dimensionAlpha[$key], $value);
            }
        }
        //整體alpha 因為key值混亂 算不出cronbach alpha 重新設定key值
        foreach ($alphaarray as $key => $val) {
            $new_key = 0;
            ksort($alphaarray[$key]);
            foreach ($alphaarray[$key] as $innerkey => $innervalue) {
                $alphaarray[$key][$new_key++] = $innervalue;
                // print_r($innerkey."to ->".$new_key."\n");
                unset($alphaarray[$key][$innerkey]);
            }
        }
        $alpha = array();
        //算各構面 alpha
        // print_r($alphaarray);
        // print_r($dimensionAlpha);
        foreach ($dimensionAlpha as $key => $value) {
            $ca = new CronbachAlpha();
            $ca->LoadData($value);
            // print_r(round($ca->CalculateCronbachAlpha(), 4));
            $alpha[$key] = round($ca->CalculateCronbachAlpha(), 4);
        }
        $ca = new CronbachAlpha();
        //算整體cronbach alpha
        $ca->LoadData($alphaarray);
        $alpha["整體"] = round($ca->CalculateCronbachAlpha(), 4);

        //收斂效度回傳陣列宣告
        $MinVality = array();

        //區別效度 使用corr矩陣輔助及dimcountArr各構面題數輔助

        //totalQuestion = 總題數
        $totalQuestion = 0;
        foreach ($dimcountArr as $key => $value) {
            $totalQuestion += $value;
        }
        //上下限
        $max = 0;
        $min = 0;
        $now = 1;
        $rejectTime = 0;
        $compareTime = 0;
        $test = 0;
        foreach ($dimcountArr as $dim => $count) {
            $min = $max;
            $max += $count;
            foreach ($corr[$dim] as $question => $eachCorr) {
                for ($i = $now++; $i < $totalQuestion; $i++) {
                    for ($j = $now; $j < $totalQuestion; $j++) {
                        if (!($j >= $min && $j < $max)) {
                            if ($eachCorr[$i] < $eachCorr[$j]) {
                                $rejectTime++;
                            }

                            $compareTime++;
                        }
                    }
                }
            }

            //算區別效度時順便算收斂效度
            $limit = 0;
            $eachMinVality = 1;
            // 兩題之間的區別效度
            //q1為其中一題的敘述
            //因資料格式設計 q2為其中一題的索引 需再找出題目敘述
            $q1 = "";
            $q2 = 0;
            foreach ($corr as $innerdim => $question) {
                foreach ($question as $key => $everycorr) {
                    if (($limit) > $max) {
                        break;
                    }
                    for ($i = 0; $i < $totalQuestion; $i++) {
                        if ($limit < $min) {
                            if ($i >= $min && $i < $max) {
                                // print_r($everycorr[$i]."\n");
                                if ($everycorr[$i] < $eachMinVality) {
                                    $eachMinVality = $everycorr[$i];
                                    $q1 = $key;
                                    $q2 = $i;
                                }

                            }
                        } else {
                            if ($i < $max) {
                                // print_r($everycorr[$i]."\n");
                                if ($everycorr[$i] < $eachMinVality) {
                                    $eachMinVality = $everycorr[$i];
                                    $q1 = $key;
                                    $q2 = $i;
                                }

                            }
                        }
                    }
                    $limit++;
                }
                if (($limit) >= $max) {
                    break;
                }

            }
            $MinVality[$innerdim]["value"] = $eachMinVality;
            $MinVality[$innerdim]["q1"] = $q1;
            $MinVality[$innerdim]["q2"] = $indexToQuestionArr[$q2];
            // print_r("__________________________________".$innerdim.$eachMinVality."\n");
        }
        $DiscriminantValidity["rejectTime"] = $rejectTime;
        $DiscriminantValidity["compareTime"] = $compareTime;

        //製作輸出檔案
        $result = array();
        
        //alpha
        array_push($result, ["Cronbach alpha"]);
        foreach ($alpha as $key => $value) {
            array_push($result, ["",$key,$value]);
        }
        //折半信度
        array_push($result, [" "]);
        array_push($result, ["折半信度"]);
        array_push($result, ["",$halfReliablity]);
        
        //區別效度
        array_push($result, [" "]);
        array_push($result, ["區別效度"]);
        array_push($result, ["","違反次數",$DiscriminantValidity["rejectTime"]]);
        array_push($result, ["","比較次數",$DiscriminantValidity["compareTime"]]);

        //回應數量
        array_push($result, [" "]);
        array_push($result, ["回應數量"]);
        array_push($result, ["",$responses->count()]);

        //收斂效度
        array_push($result, [" "]);
        array_push($result, ["收斂效度"]);
        foreach ($MinVality as $key => $value) {
            array_push($result, ["",$key,$value["value"]]);
            array_push($result, ["","",$value["q1"]]);
            array_push($result, ["","",$value["q2"]]);
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
        return "基本資料";
    }
}