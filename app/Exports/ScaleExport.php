<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ScaleExport implements WithMultipleSheets
{
    use Exportable;

    protected $ScaleID;

    public function __construct(int $ScaleID)
    {
        $this->ScaleID = $ScaleID;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        $sheets[0] = new ScaleBasicData($this->ScaleID);
        $sheets[1] = new ScaleCorr($this->ScaleID);
        $sheets[2] = new ScaleCompareTable($this->ScaleID);

        return $sheets;
    }
}