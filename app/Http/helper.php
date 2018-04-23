<?php

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