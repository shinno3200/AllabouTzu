<?php

namespace App\Http\Controllers;

use App\Models\EatingHistory;
use App\Models\EatMaster;
use App\Models\JudgeHistory;
use App\Models\Ore_Master;
use App\Models\UsedCalHistory;
use App\Services\CommonInfoService;
use App\Services\EatingHistoryService;
use Illuminate\Http\Request;

class DietterController extends Controller
{
    protected $useKCal = 0;

    protected $totalKCal = 0;

    protected $judgeResultNum = 0;

    protected $taisha = 0;

    protected $bmi = 0;

    protected $currentDate;

    protected $cInfoService;

    protected $eatingHistoryService;

    public function __construct(CommonInfoService $cInfoService, EatingHistoryService $eatingHistoryService)
    {
        $this->cInfoService = $cInfoService;
        $this->eatingHistoryService = $eatingHistoryService;
    }

    public function index()
    {
        // モデルクラスをインスタンス化してoreMasterテーブルのデータをビュー側で使えるようにする
        // phpinfo();
        $currentDate = date('Y-m-d');
        $oreMaster = Ore_Master::all();
        // サービスクラスで基礎代謝とBMIを計算
        $taisha = $this->cInfoService->calcTaisha($oreMaster);
        $bmi = $this->cInfoService->calcBMI($oreMaster);

        $judgeHistory = JudgeHistory::whereBetween('inputDate', [now()->subDays(7), $currentDate])->ORDERBY('inputDate', 'DESC')->get();
        $weekTotalKCal = 0;
        $weekUseKCal = 0;
        $weekJudgeResultNum = 0;
        foreach ($judgeHistory as $history) {
            $weekTotalKCal += $history['totalKcal'];
            $weekUseKCal += $history['lostKcal'];
        }
        $weekJudgeResultNum = $weekTotalKCal - $weekUseKCal;

        // dietter.blade.phpに$oreMasterをわたす
        return view('dietter', ['oreMaster' => $oreMaster, 'taisha' => $taisha, 'bmi' => $bmi, 'currentDate' => $currentDate,
            'weekTotalKCal' => $weekTotalKCal, 'weekUseKCal' => $weekUseKCal, 'weekJudgeResultNum' => $weekJudgeResultNum,
            'totalKCal' => 0, 'useKCal' => 0, 'judgeResultNum' => 0, 'judgeHistory' => $judgeHistory]);
    }

    public function eatMasterStore(Request $request)
    {
        // バリデーションなどの処理を追加する場合はここに記述

        // データの登録
        $eatMaster = new EatMaster();
        $eatMaster->eat = $request->eatInfo;
        $eatMaster->kCal = $request->kCalInfo;
        $eatMaster->save();

        return redirect('/dietter')->with('message', 'カロリー登録が完了しました！');
    }

    public function eatingHistoryStore(Request $request)
    {
        $currentDate = date('Y-m-d');
        // バリデーションなどの処理を追加する場合はここに記述
        if (empty($request->eating[0])) {
            return redirect('/dietter')->with('message', '食品を入力してください');
        }

        // データの登録
        for ($i = 0; $i < count($request->eating); $i++) {
            $eatingHisotry = new EatingHistory();
            $eatingHisotry->inputDate = $currentDate;
            $eatingHisotry->eat = $request->eating[$i];
            $eatingHisotry->num = $request->number[$i];
            $eatingHisotry->save();
        }

        $usedCalHistory = UsedCalHistory::where('inputDate', $currentDate)->get();
        if ($usedCalHistory->isEmpty()) {
            $usedCalHistory = new UsedCalHistory();
            $usedCalHistory->inputDate = $currentDate;
            $usedCalHistory->usedCal = $request->usekCal;
            $usedCalHistory->save();
        }

        return redirect('/dietter')->with('message', '記録が完了しました！');
    }

    public function judge(Request $request)
    {
        $currentDate = date('Y-m-d');
        $eatingHistory = EatingHistory::where('inputDate', $currentDate)->get();
        $eatingArray = [];
        $eatingArray = $this->eatingHistoryService->getEatingArray($eatingHistory);

        $usedCalHistory = UsedCalHistory::where('inputDate', $currentDate)->get();
        if ($usedCalHistory->isEmpty()) {
            return redirect('/dietter')->with('message', '本日の消費カロリーを入力してください。');
        }
        $useKCal = $usedCalHistory[0]->usedCal;

        $eatMaster = EatMaster::whereIn('eat', $eatingArray)->get();
        $totalKCal = 0;
        //4:総合カロリー = 3のカロリー * 個数
        foreach ($eatingHistory as $eatData) {
            foreach ($eatMaster as $kCalData) {
                if ($eatData['eat'] === $kCalData['eat']) {
                    $totalKCal += $kCalData['kCal'] * $eatData['num'];
                    break;
                }
            }
        }

        //5:判定 = 総合カロリー - 消費カロリー
        $judgeResultNum = $totalKCal - ($request->hiddenBMI * 1.2 + $useKCal); //消費カロリー = (bmi * 1.2) + 消費カロリー;

        return redirect('/dietter')->with('message', '判定しました！')
            ->with('totalKCal', $totalKCal)
            ->with('useKCal', $useKCal)
            ->with('judgeResultNum', $judgeResultNum);
    }
}
