<?php
namespace App\Services;

class EatingHistoryService {
    public function getEatingArray($eatingHistory) {
        $eatingArray = array();
        foreach ($eatingHistory as $history) { // 実行結果の初めの行から順に取得
            array_push($eatingArray, $history['eat']);
        }

        return $eatingArray;
    }
}