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
        <form id="kCal" action="dietter.php" method="post">
            <div class="container">
                <div class="item">食品</div><input type="text" name="eatInfo">
                <input type="submit" value="登録" name="kCalInputButton">
            </div>
            <div class="container">
                <div class="item">カロリー</div>
                <input type="text" name="kCalInfo" value="">kcal
            </div>
        </form>
    </div>
</div>

</body>
</html>