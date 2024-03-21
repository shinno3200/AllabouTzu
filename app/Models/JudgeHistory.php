<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JudgeHistory extends Model
{
    use HasFactory;

    protected $table = 'judgeHistory';

    public $timestamps = false;
}
