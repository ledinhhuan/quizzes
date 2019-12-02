<?php

namespace App\Services;

class ChartService
{
    /**
     * Split level by weak, average, good, excellent
     *
     * @param $chart
     * @return mixed
     */
    public function partitionLevel($chart)
    {
        $total = $chart->reduce(function ($carry, $item) {
            if ($item >= 0 && $item < 4) {
                $carry['weak'][] = $item;
            }
            if ($item >= 4 && $item < 7) {
                $carry['average'][] = $item;
            }
            if ($item >= 7 && $item < 9) {
                $carry['good'][] = $item;
            }
            if ($item >= 9 && $item <= 10){
                $carry['excellent'][] = $item;
            }
            return $carry;
        });

        return $total;
    }

    /**
     * Calculate by level
     *
     * @param $total
     * @param $chart
     * @return array
     */
    public function calculateLevel($total ,$chart)
    {
        $calculate = [
            ['name' => 'weak', 'y' => $this->roundPercent(isset($total['weak']) ? count($total['weak']) : 0, $chart),],
            ['name' => 'average', 'y' => $this->roundPercent(isset($total['average']) ? count($total['average']) : 0, $chart),],
            ['name' => 'good', 'y' => $this->roundPercent(isset($total['good']) ? count($total['good']) : 0, $chart),],
            ['name' => 'excellent', 'y' => $this->roundPercent(isset($total['excellent']) ? count($total['excellent']) : 0, $chart),],
        ];

        return $calculate;
    }

    /**
     * Round percent
     *
     * @param $total
     * @param $chart
     * @return float|null
     */
    protected function roundPercent($total, $chart)
    {
        return isset($total) ? round((($total / count($chart)) * 100 ), 1) : 0;
    }
}
