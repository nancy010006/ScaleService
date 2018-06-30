<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ScaleExport implements WithMultipleSheets
{
    use Exportable;

    protected $ScaleID;
    protected $StartDate;
    protected $EndDate;

    public function __construct(int $ScaleID,String $StartDate = null,String $EndDate = null)
    {
        $this->ScaleID = $ScaleID;
        $this->StartDate = $StartDate;
        $this->EndDate = $EndDate;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        $sheets[0] = new ScaleBasicData($this->ScaleID,$this->StartDate,$this->EndDate);
        $sheets[1] = new ScaleCorr($this->ScaleID,$this->StartDate,$this->EndDate);
        $sheets[2] = new ScaleCompareTable($this->ScaleID,$this->StartDate,$this->EndDate);
        $sheets[3] = new OriginData($this->ScaleID,$this->StartDate,$this->EndDate);

        return $sheets;
    }
}