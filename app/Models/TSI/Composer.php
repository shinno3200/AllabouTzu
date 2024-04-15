<?php

namespace App\Models\TSI;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Composer extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $hidden = ['created_at', 'updated_at'];
}
