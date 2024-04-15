<?php

namespace App\Services;

class CommonInfoService
{
    public function calcTaisha($oreMaster)
    {
        //基礎代謝 = 13.397×体重kg＋4.799×身長cm−5.677×年齢+88.362
        $oreArray = $oreMaster->toArray();

        return 13.397 * $oreArray[0]['weight'] + 4.799 * $oreArray[0]['tall'] - 5.677 * $oreArray[0]['old'] + 88.362;
    }

    public function calcBMI($oreMaster)
    {
        //BMI ＝ 体重kg ÷ (身長m)2
        $oreArray = $oreMaster->toArray();

        return round($oreArray[0]['weight'] / (($oreArray[0]['tall'] / 100) * ($oreArray[0]['tall'] / 100)), 2);
    }
}
