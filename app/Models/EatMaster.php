<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EatMaster extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    protected $table = 'eatMaster';

    public $timestamps = false;
}
