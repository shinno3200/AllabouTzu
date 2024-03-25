<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="{{ asset('css/dietter.css') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dietter</title>
</head>
<body>
<h2>Dietter</h2>

@if (session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif

<div class="container">
    <div class="column-container infoArea">
        <p>基礎情報</p>
        <div class="container">
            <div class="item">年齢：{{ $oreMaster[0]['old'] }}</div>
            <div class="item">身長：{{ $oreMaster[0]['tall'] }}cm</div>
            <div class="item">体重：{{ $oreMaster[0]['weight'] }}kg</div>
        </div>
        <div class="container">
            <div class="item">BMI：{{ $bmi }}</div>
            <div class="item">基礎代謝：{{ $taisha }}</div>
        </div>
    </div>

    <div class="column-container">
        <p>カロリー入力・登録</p>
        <form id="kCal" action="{{ route('kCalInput.submit') }}" method="post">
            @csrf
            <div class="container">
                <div class="item">食品</div><input type="text" name="eatInfo" id="eatInfo">
                <input type="button" value="登録" name="kCalInput" onclick="validateForm()">
            </div>
            <div class="container">
                <div class="item">カロリー</div>
                <input type="text" name="kCalInfo" value="" id="kCalInfo">kcal
            </div>
        </form>
    </div>
</div>

<hr>

<div class="container">
    <div class="column-container inputArea">
        <p>記録({{ $currentDate }})</p>
        <form id="record" action="{{ route('eatingInput.submit') }}" method="post">
            @csrf
            <div class="container">
                <div class="item infoHead">消費カロリー</div>
                <input type="text" name="usekCal" id="" value="{{ $taisha }}">kcal
                <input type="submit" value="登録" name="recordButton">
            </div>
            <table id="eatInput">
                <tr>
                    <th class="infoHead">食品</th>
                    <th class="infoHead">個数</th>
                    <td><input type="button" value="行追加" onclick="addRow()"></td>
                </tr>
                <tr>
                    <td><input type="text" name="eating[]" class="eatingBox"></td>
                    <td>
                        <select name="number[]" class="numberSelect">
                            <option value=1>1</option>
                            <option value=2>2</option>
                            <option value=3>3</option>
                        </select>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    
    <div class="column-container">
        <p>判定</p>
        <form id="judge" action="{{ route('judge.submit') }}" method="post">
            @csrf
            <table>
                <tr>
                    <th></th>
                    <th class="resultHead">総合カロリー</th>
                    <th class="resultHead">消費カロリー</th>
                    <th class="resultHead">判定結果</th>
                    <td><input type="submit" value="判定" name="judgeButton"></td>
                </tr>
                <tr>
                    <th class="infoHead">本日</th>
                    <td><input type="text" name="totalKCal" class="unInputItem" value="{{ session('totalKCal') }}"></td>
                    <td><input type="text" name="useKCal" class="unInputItem" value="{{ session('useKCal') * 1.2 }}"></td>
                    <td><input type="text" name="result" class="unInputItem" value="{{ session('judgeResultNum') }}"></td>
                    <td><input type="submit" value="登録" name="judgeCorrectButton"></td>
                    <input type="hidden" name="hiddenBMI" value="{{ $bmi }}">
                </tr>
                <tr>
                    <th class="infoHead">週間</th>
                    <td><input type="text" name="" class="unInputItem" value="{{ $weekTotalKCal }}"></td>
                    <td><input type="text" name="" class="unInputItem" value="{{ $weekUseKCal }}"></td>
                    <td><input type="text" name="" class="unInputItem" value="{{ $weekJudgeResultNum }}"></td>
                </tr>
            </table>
        </form>

        <p>履歴</p>
        <table>
            <tr>
                <th class="infoHead">日付</th>
                <th class="infoHead">総合カロリー</th>
                <th class="infoHead">消費カロリー</th>
                <th class="resultHead">判定結果</th>
            </tr>
            @foreach($judgeHistory as $judgeData)
            <tr>
                <td><input type="text" name="" class="unInputItem" value="{{ $judgeData['inputDate'] }}"></td>
                <td><input type="text" name="" class="unInputItem" value="{{ $judgeData['totalKcal'] }}"></td>
                <td><input type="text" name="" class="unInputItem" value="{{ $judgeData['lostKcal'] }}"></td>
                <td><input type="text" name="" class="unInputItem" value="{{ $judgeData['judgeResult'] }}"></td>
            </tr>
            @endforeach
        </table>
    </div>

</div>

<script>
function addRow() {
    // 新しい<tr>要素を作成
    var table = document.getElementById("eatInput");
    var newRow = table.insertRow();

    // 新しい<td>要素を作成し、テキストを設定
    var cell1 = newRow.insertCell(0);
    var cell2 = newRow.insertCell(1);
    cell1.innerHTML = "<td><input type="+"text"+" name="+"eating[]"+" class="+"eatingBox"+"></td>";
    cell2.innerHTML = "<td><select name="+"number[]"+" class="+"numberSelect"+"><option value=1>1</option><option value=2>2</option><option value=3>3</option></select></td>";
}

function validateForm() {
    var eatInfo = document.getElementById('eatInfo').value;
    var kCalInfo = document.getElementById('kCalInfo').value;

    // バリデーションチェック
    if (eatInfo.trim() === '') {
        alert('食品を入力してください。');
        return;
    }
    if (kCalInfo.trim() === '') {
        alert('カロリーを入力してください。');
        return;
    }
            
    // バリデーションが成功した場合はフォームをサブミット
    document.getElementById('kCal').submit();
}
</script>

</body>
</html>