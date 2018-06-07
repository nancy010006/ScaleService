<?php

function pushSpaceInExcel($array,$times){
    for ($i=0; $i <$times ; $i++) { 
        array_push($array,"");
    }
    return $array;
}
function standard_deviation($aValues, $bSample = false)
{
    $fMean = array_sum($aValues) / count($aValues);
    $fVariance = 0.0;
    foreach ($aValues as $i)
    {
        $fVariance += pow($i - $fMean, 2);
    }
    $fVariance /= ( $bSample ? count($aValues) - 1 : count($aValues) );
    return (float) sqrt($fVariance);
}
function jsonResponseTransfer($jsonStr){
    $response = json_decode($jsonStr);
	$result = array();
	foreach ($response as $key => $value) {
        $result[$value->qid]=$value->val;
    }
    return $result;
}
function getcovar($X,$Y){
    $xavg=array_sum($X)/count($X); //X 平均值
    $yavg=array_sum($Y)/count($Y); //Y 平均值
    $xsum=array_sum($X);           //X 總和
    $ysum=array_sum($Y);           //Y 總和
    $x_square_sum=0;               //X 平方和累計
    $y_square_sum=0;               //Y 平方和累計
    $XMD=Array();                  //X 離均差
    $YMD=Array();                  //Y 離均差
    $mdcross_sum=0;                //X,Y 離均差交乘積和
    $count=count($X);              //元素個數
    for ($i=0; $i <$count; ++$i) {
        $xdif=(float)$X[$i]-$xavg; //X 離均差
        $ydif=(float)$Y[$i]-$yavg; //Y 離均差
        $XMD[$i]=$xdif;
        $YMD[$i]=$ydif;
        $mdcross_sum += $xdif*$ydif;       //X,Y 離均差交乘積和
        $x_square_sum += pow($X[$i], 2);   //X 平方和累計
        $y_square_sum += pow($Y[$i], 2);   //Y 平方和累計
    } //end of for
    //計算樣本標準差 & 乘積
    $xstd=sqrt(($x_square_sum-pow($xsum,2)/$count)/($count-1));
    $ystd=sqrt(($y_square_sum-pow($ysum,2)/$count)/($count-1));
    $xystd=$xstd*$ystd; //兩標準差乘積
    //計算樣本共變異數
    $covar=$mdcross_sum/($count-1);
    // print_r($covar);
    //計算相關係數
    $corr=$covar/$xystd;  //答案是 0.94137554144354
    return $covar;
}

function getcorr($X,$Y){
    $xavg=array_sum($X)/count($X); //X 平均值
    $yavg=array_sum($Y)/count($Y); //Y 平均值
    $xsum=array_sum($X);           //X 總和
    $ysum=array_sum($Y);           //Y 總和
    $x_square_sum=0;               //X 平方和累計
    $y_square_sum=0;               //Y 平方和累計
    $XMD=Array();                  //X 離均差
    $YMD=Array();                  //Y 離均差
    $mdcross_sum=0;                //X,Y 離均差交乘積和
    $count=count($X);              //元素個數
    for ($i=0; $i <$count; ++$i) {
        $xdif=(float)$X[$i]-$xavg; //X 離均差
        $ydif=(float)$Y[$i]-$yavg; //Y 離均差
        $XMD[$i]=$xdif;
        $YMD[$i]=$ydif;
        $mdcross_sum += $xdif*$ydif;       //X,Y 離均差交乘積和
        $x_square_sum += pow($X[$i], 2);   //X 平方和累計
        $y_square_sum += pow($Y[$i], 2);   //Y 平方和累計
    } //end of for
    //計算樣本標準差 & 乘積
    $xstd=sqrt(($x_square_sum-pow($xsum,2)/$count)/($count-1));
    $ystd=sqrt(($y_square_sum-pow($ysum,2)/$count)/($count-1));
    $xystd=$xstd*$ystd; //兩標準差乘積
    //計算樣本共變異數
    $covar=$mdcross_sum/($count-1);
    // print_r($covar);
    //計算相關係數
    $corr=$covar/$xystd;  //答案是 0.94137554144354
    return $corr;
}