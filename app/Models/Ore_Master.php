<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ore_Master extends Model
{
    // テーブル名の指定（デフォルトではクラス名のスネークケースが使用される）
    protected $table = 'oreMaster';

    // プライマリキーの指定（デフォルトでは'id'が使用される）
    protected $primaryKey = 'name';

    // モデルのタイムスタンプの自動更新を無効にする
    public $timestamps = false;

    // モデルの可変属性（Mass Assignment）を指定
    protected $fillable = ['old', 'tall', 'weight', 'workType'];
}
