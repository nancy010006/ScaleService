<?php
/**
 * CronbachAlpha
 * Calculates the Cronbach's Alpha Coefficient for a given set.
 *
 * @author ROMAN GRANOVSKYI, AUSSIEDEV CONSULTING
 * @email romziki@gmail.com
 * @copyright Copyright (c) 2011
 * @version 1
 * @license : GNU General Public License (GPL)
 * @access public
 */

/**
 * * Example Usage:
 * <?php
 * require("inc/class.cronbach.alpha.php");
 * $ca=new CronbachAlpha();
 *
 * $data=array(array(100,100,100,75,100,100,100,100,25,100), //1
 * array(75,75,100,75,75,75,75,75,25,50),
 * array(75,100,100,75,100,100,75,100,50,100));
 * $ca->LoadData($data);
 * $alpha=$ca->CalculateCronbachAlpha();
 * ?>
 *
 *  in the above example we have 3 sets = 3 respondents and 10 items each.
 *  The expected result is 0.922305764411
 *
 *
 */
namespace App;

class CronbachAlpha {
    private $DataArray = array(); //Data[Q][P]	-   Question -> Person

    var $ItemVariances = array();

    var $RespondentMeans = array();
    var $TotalValuePerRespondent = array();

	/* Use this to ignore items with zero variance. Just like SPSS does */

    var $IgnoreWithZeroVariance = true;

    private $_resp_count = null;
    private $_questions_count = null;

    function LoadData($data)
    {
        $data2 = array();
        foreach($data as $resp => $questions) {
            foreach($questions as $k => $q) {
                $data2[$k][$resp] = $q;
            }
        }
        $this->DataArray = $data2;
    }

    function CalculateCronbachAlpha()
    {
        $this->calculateItemVariances();

        $stdev = $this->calculateStdDevRespondents();

        $vars = $this->calculateSumOfItemVariances();

        $cronbachalpha = $this->countNumberOfQuestions() / (($this->countNumberOfQuestions())-1);

        $part2 = $vars / ($stdev * $stdev);

        $cronbachalpha = $cronbachalpha * (1 - $part2);

        return $cronbachalpha;
    }

    function calculateTotalRespondents()
    {
        /* get their means */

        for($i = 0;$i < $this->countNumberOfRespondents();$i++) {
            $this->TotalValuePerRespondent[$i] = 0.00;

            foreach($this->DataArray as $Q => $Person) {
                $this->TotalValuePerRespondent[$i] += $Person[$i];
            }
        }

        return $this->TotalValuePerRespondent;
    }

    function calculateStdDevRespondents()
    {
        $data = $this->calculateTotalRespondents();
        $total = 0;
        $avg = $this->calculateMeanRespondents();
        foreach($data as $d) {
            $total += ($d - $avg) * ($d - $avg);
        }

        $cnt = $this->countNumberOfRespondents()-1;
        return sqrt($total / $cnt);
    }

    function calculateMeanRespondents()
    {
        $total = 0;
        $data = $this->calculateTotalRespondents();
        for($i = 0;$i < $this->countNumberOfRespondents();$i++) {
            $total += $data[$i];
        }

        $avg = $total / $this->countNumberOfRespondents();
        return $avg;
    }

    function calculateSumOfItemVariances()
    {
        $sum = 0.00;
        foreach($this->ItemVariances as $k => $value) {
            $sum += $value;
        }
        return (Float)$sum;
    }

    function calculateItemVariances()
    {
        $to_unset = array();
        for($q = 0;$q < $this->countNumberOfQuestions();$q++) {
            /* this is just a standard deviation */

            /* 1.calculate average for this question */
            $total = 0;

            for($p = 0;$p < count($this->DataArray[$q]);$p++) {
                $total += $this->DataArray[$q][$p];
            }

            if (count($this->DataArray[$q]) == 0)throw new Exception("division by zero");
            $average = $total / count($this->DataArray[$q]);

            /* 2. compute the difference between each value and average = all squared */

            $total_sq = 0;
            for($p = 0;$p < count($this->DataArray[$q]);$p++) {
                $sq = ($this->DataArray[$q][$p] - $average) * ($this->DataArray[$q][$p] - $average);

                $total_sq += $sq;
            }

            $cnt = count($this->DataArray[$q])-1;
            $sigma_sq = $total_sq / $cnt;

            if ($sigma_sq == 0) {
                // unset those where sigma_sq=0;
                $to_unset[] = $q;
            }

            $this->ItemVariances[$q] = $sigma_sq;
        }

        if (count($this->ItemVariances) != $this->countNumberOfQuestions())throw new Exception("Incosisten data");

        if ($this->IgnoreWithZeroVariance) {
            foreach($to_unset as $t) {
                unset($this->ItemVariances[$t]);
                unset($this->DataArray[$t]);
            }
            $this->DataArray = array_values($this->DataArray);
            $this->ItemVariances = array_values($this->ItemVariances);
            $this->_questions_count -= count($to_unset);
        }
    }

    function countNumberOfRespondents()
    {
        if ($this->_resp_count == null) {
            $this->_resp_count = count($this->DataArray[0]);
        }

        return $this->_resp_count;
    }

    function countNumberOfQuestions()
    {
        if ($this->_questions_count == null) {
            $this->_questions_count = count($this->DataArray);
        }

        return $this->_questions_count;
    }
} ;
