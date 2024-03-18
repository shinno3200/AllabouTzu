<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ore_Master;
use App\Services\CommonInfoService;
use App\Http\Controllers\Controller;

class DietterController extends Controller
{
    protected
        $useKCal = 0,
        $totalKCal = 0,
        $judgeResultNum = 0,
        $weekTotalKCal = 0,
        $weekUseKCal = 0,
        $weekJudgeResultNum = 0,
        $taisha =0,
        $bmi = 0,
        $currentDate,
        $cInfoService;

    public function __construct(CommonInfoService $cInfoService)
    {
        $this->cInfoService = $cInfoService;
    }

    public function index() {
        // モデルクラスをインスタンス化してoreMasterテーブルのデータをビュー側で使えるようにする
        $oreMaster = Ore_Master::all();
        $currentDate = date("Y-m-d");
        // サービスクラスで基礎代謝とBMIを計算
        $taisha = $this->cInfoService->calcTaisha($oreMaster);
        $bmi = $this->cInfoService->calcBMI($oreMaster);

        // dietter.blade.phpに$oreMasterをわたす
        return view('dietter', ['oreMaster' => $oreMaster, 'taisha' => $taisha, 'bmi' => $bmi]);
    }
}