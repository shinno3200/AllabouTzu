<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EatingHistory extends Model
{
    use HasFactory;

    protected $table = 'eatingHistory';

    public $timestamps = false;
}
