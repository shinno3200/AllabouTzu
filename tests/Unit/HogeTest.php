<?php

// テストで使う関数をインポート
use PHPUnit\Framework\TestCase;

// テストしたいクラスをインポート
require_once 'Hoge.php';

// ✅テストクラス
class HogeTest extends TestCase
{
    // ---------------------------------------------------------------------
    // 準備
    // ---------------------------------------------------------------------

    // ✅テストで使う変数の準備
    private $hoge;

    public function setUp(): void
    {
        // 必ず親クラスのsetUpを呼んでおくこと
        parent::setUp();

        // Hogeインスタンスを準備（各関数でインスタンスを生成せずに済む）
        $this->hoge = new Hoge();
    }

    // ---------------------------------------------------------------------
    // テスト
    // ---------------------------------------------------------------------

    // ✅Sample01関数のテスト
    public function testSample01()
    {
        // ✅'これはSample01です'が返ってくるか？
        $this->assertEquals('これはSample01です', $this->hoge->Sample01());
    }

    // Sample02関数のテスト
    public function testSample02()
    {
        // 2が返ってくるか？
        $this->assertEquals(2, $this->hoge->Sample02(1, 1));

        // 4が返ってくるか？
        $this->assertEquals(4, $this->hoge->Sample02(2, 2));

        // 10が返ってくるか？
        $this->assertEquals(10, $this->hoge->Sample02(4, 6));
    }
}
