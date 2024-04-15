<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="dietter.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dietter</title>
</head>
<body>
<h2>Dietter</h2>

<?php
    //履歴検索
    $pdo = new PDO('mysql:host=localhost;dbname=dietter', 'root', 'root');
$stmt = $pdo->prepare('SELECT inputDate, totalKcal, lostKcal, judgeResult FROM judgeHistory WHERE inputDate between DATE_SUB(NOW(), INTERVAL 7 DAY) and :cDate ORDER BY inputDate DESC');
$stmt->bindParam(':cDate', $currentDate);
$stmt->execute();
$judgeDataList = []; // 配列作成
while ($row = $stmt->fetch()) { // 実行結果の初めの行から順に取得
    array_push($judgeDataList, ['inputDate' => $row['inputDate'], 'totalKcal' => $row['totalKcal'], 'lostKcal' => $row['lostKcal'], 'judgeResult' => $row['judgeResult']]);
    $weekTotalKCal += $row['totalKcal'];
    $weekUseKCal += $row['lostKcal'];
}
$weekJudgeResultNum = $weekTotalKCal - $weekUseKCal;
?>

<?php
    //記録
    if (! empty($_POST['recordButton'])) {
        try {
            //食品経歴の登録
            $eats = $_POST['eating'];
            $nums = $_POST['number'];
            $recordPdo = new PDO('mysql:host=localhost;dbname=dietter', 'root', 'root');
            $stmt = $recordPdo->prepare('INSERT INTO eatingHistory (inputDate, eat, num) VALUES (:cDate, :eat, :num)');
            for ($i = 0; $i < count($eats); $i++) {
                $stmt->bindParam(':cDate', $currentDate);
                $stmt->bindParam(':eat', $eats[$i]);
                $stmt->bindParam(':num', $nums[$i]);

                $stmt->execute();
            }

            //消費Kcal経歴の登録
            $stmt = $recordPdo->prepare('SELECT * FROM usedCalHistory WHERE inputDate = :cDate');
            $stmt->bindParam(':cDate', $currentDate);
            $stmt->execute();
            if (! $stmt->fetch()) {
                $stmt = $recordPdo->prepare('INSERT INTO usedCalHistory (inputDate, usedCal) VALUES (:cDate, :usedCal)');
                $stmt->bindParam(':cDate', $currentDate);
                $stmt->bindParam(':usedCal', $_POST['usekCal']);
                $stmt->execute();
            }

            $recordPdo = null;

            echo '消費カロリーと食べた飲食品を登録しました。';
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
?>

<?php
    //判定処理
    if (! empty($_POST['judgeCorrectButton'])) {
        //1:食品経歴から当日の食品と個数を取得
        $pdo = new PDO('mysql:host=localhost;dbname=dietter', 'root', 'root');
        $stmt = $pdo->prepare('SELECT eat, num FROM eatingHistory WHERE inputDate = :cDate');
        $stmt->bindParam(':cDate', $currentDate);
        $stmt->execute();
        $eatDataList = []; // 配列作成
        $eatingInStr = '';
        while ($row = $stmt->fetch()) { // 実行結果の初めの行から順に取得
            array_push($eatDataList, ['eat' => $row['eat'], 'num' => $row['num']]);
            $eatingInStr = $eatingInStr."'".$row['eat']."',";
        }
        $eatingInStr = substr($eatingInStr, 0, -1);

        //2:消費カロリー経歴から当日の消費カロリーを取得
        $stmt = $pdo->prepare('SELECT usedCal FROM usedCalHistory WHERE inputDate = :cDate');
        $stmt->bindParam(':cDate', $currentDate);
        $stmt->execute();

        $useKCal = $stmt->fetch()[0]; // 消費カロリー

        //3:1で取得した食品を条件に食品マスタからカロリーを取得
        $stmt = $pdo->prepare("SELECT eat, kCal FROM eatMaster WHERE eat in ($eatingInStr)");
        $stmt->execute();
        $calDataList = []; // 配列作成
        while ($row = $stmt->fetch()) { // 実行結果の初めの行から順に取得
            array_push($calDataList, ['eat' => $row['eat'], 'kCal' => $row['kCal']]);
        }

        //4:総合カロリー = 3のカロリー * 個数
        foreach ($eatDataList as $eatData) {
            foreach ($calDataList as $kCalData) {
                if ($eatData['eat'] === $kCalData['eat']) {
                    $totalKCal += $kCalData['kCal'] * $eatData['num'];
                    break;
                }
            }
        }

        //5:判定 = 総合カロリー - 消費カロリー
        $judgeResultNum = $totalKCal - ($bmi * 1.2 + $useKCal); //消費カロリー = (bmi * 1.2) + 消費カロリー;

        $pdo = null;
    }
?>

<?php
    //判定処理
    if (! empty($_POST['judgeButton'])) {
        //1:食品経歴から当日の食品と個数を取得
        $pdo = new PDO('mysql:host=localhost;dbname=dietter', 'root', 'root');
        $stmt = $pdo->prepare('SELECT eat, num FROM eatingHistory WHERE inputDate = :cDate');
        $stmt->bindParam(':cDate', $currentDate);
        $stmt->execute();
        $eatDataList = []; // 配列作成
        $eatingInStr = '';
        while ($row = $stmt->fetch()) { // 実行結果の初めの行から順に取得
            array_push($eatDataList, ['eat' => $row['eat'], 'num' => $row['num']]);
            $eatingInStr = $eatingInStr."'".$row['eat']."',";
        }
        $eatingInStr = substr($eatingInStr, 0, -1);

        //2:消費カロリー経歴から当日の消費カロリーを取得
        $stmt = $pdo->prepare('SELECT usedCal FROM usedCalHistory WHERE inputDate = :cDate');
        $stmt->bindParam(':cDate', $currentDate);
        $stmt->execute();

        $useKCal = $stmt->fetch()[0]; // 消費カロリー

        //3:1で取得した食品を条件に食品マスタからカロリーを取得
        $stmt = $pdo->prepare("SELECT eat, kCal FROM eatMaster WHERE eat in ($eatingInStr)");
        $stmt->execute();
        $calDataList = []; // 配列作成
        while ($row = $stmt->fetch()) { // 実行結果の初めの行から順に取得
            array_push($calDataList, ['eat' => $row['eat'], 'kCal' => $row['kCal']]);
        }

        //4:総合カロリー = 3のカロリー * 個数
        foreach ($eatDataList as $eatData) {
            foreach ($calDataList as $kCalData) {
                if ($eatData['eat'] === $kCalData['eat']) {
                    $totalKCal += $kCalData['kCal'] * $eatData['num'];
                    break;
                }
            }
        }

        //5:判定 = 総合カロリー - 消費カロリー
        $judgeResultNum = $totalKCal - ($bmi * 1.2 + $useKCal); //消費カロリー = (bmi * 1.2) + 消費カロリー;

        $pdo = null;
    }
?>

<?php
    //判定結果登録
    if (! empty($_POST['judgeCorrectButton'])) {
        $pdo = new PDO('mysql:host=localhost;dbname=dietter', 'root', 'root');
        $stmt = $pdo->prepare('INSERT INTO judgeHistory (inputDate, totalKcal, lostKcal, judgeResult) VALUES (:cDate, :tKcal, :lKcal, :result)');
        $stmt->bindParam(':cDate', $currentDate);
        $stmt->bindParam(':tKcal', $_POST['totalKCal']);
        $stmt->bindParam(':lKcal', $_POST['useKCal']);
        $stmt->bindParam(':result', $_POST['result']);
        $stmt->execute();
        $pdo = null;

        echo '判定結果を登録しました。';
    }
?>

<?php
    //カロリー登録
    if (! empty($_POST['kCalInputButton'])) {
        $pdo = new PDO('mysql:host=localhost;dbname=dietter', 'root', 'root');
        $stmt = $pdo->prepare('INSERT INTO eatMaster (eat, kCal) VALUES (:eat, :cal)');
        $stmt->bindParam(':eat', $_POST['eatInfo']);
        $stmt->bindParam(':cal', $_POST['kCalInfo']);
        $stmt->execute();
        $pdo = null;

        echo 'カロリーを登録しました。';
    }
?>


<div class="container">
    <div class="column-container infoArea">
        <p>基礎情報</p>
        <div class="container">
            <div class="item">年齢：<?php echo $ore['old'] ?></div>
            <div class="item">身長：<?php echo $ore['tall'] ?>cm</div>
            <div class="item">体重：<?php echo $ore['weight'] ?>kg</div>
        </div>
        <div class="container">
            <div class="item">BMI：<?php echo $bmi ?></div>
            <div class="item">基礎代謝：<?php echo $taisha ?></div>
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

<hr>

<div class="container">
    <div class="column-container inputArea">
        <p>記録(<?php echo $currentDate ?>)</p>
        <form id="record" action="dietter.php" method="post">
            <div class="container">
                <div class="item infoHead">消費カロリー</div>
                <input type="text" name="usekCal" id="" value="<?php echo $taisha ?>">kcal
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
        <form id="judge" action="dietter.php" method="post">
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
                    <td><input type="text" name="totalKCal" class="unInputItem" value="<?php echo $totalKCal ?>"></td>
                    <td><input type="text" name="useKCal" class="unInputItem" value="<?php echo $useKCal + $bmi * 1.2 ?>"></td>
                    <td><input type="text" name="result" class="unInputItem" value="<?php echo $judgeResultNum ?>"></td>
                    <td><input type="submit" value="登録" name="judgeCorrectButton"></td>
                </tr>
                <tr>
                    <th class="infoHead">週間</th>
                    <td><input type="text" name="" class="unInputItem" value="<?php echo $weekTotalKCal ?>"></td>
                    <td><input type="text" name="" class="unInputItem" value="<?php echo $weekUseKCal ?>"></td>
                    <td><input type="text" name="" class="unInputItem" value="<?php echo $weekJudgeResultNum ?>"></td>
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
            <?php foreach ($judgeDataList as $judgeData) { ?>
            <tr>
                <td><input type="text" name="" class="unInputItem" value="<?php echo $judgeData['inputDate'] ?>"></td>
                <td><input type="text" name="" class="unInputItem" value="<?php echo $judgeData['totalKcal'] ?>"></td>
                <td><input type="text" name="" class="unInputItem" value="<?php echo $judgeData['lostKcal'] ?>"></td>
                <td><input type="text" name="" class="unInputItem" value="<?php echo $judgeData['judgeResult'] ?>"></td>
            </tr>
            <?php } ?>
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
</script>
    
</body>
</html>