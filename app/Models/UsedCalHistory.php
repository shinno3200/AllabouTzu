<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsedCalHistory extends Model
{
    use HasFactory;

    protected $table = 'usedCalHistory';

    public $timestamps = false;
}
