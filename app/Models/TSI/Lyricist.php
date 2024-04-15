<?php

namespace App\Models\TSI;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lyricist extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $hidden = ['created_at', 'updated_at'];

    public function songs()
    {
        return $this->belongsToMany(Song::class, 'song_lyricist_composer_arranger', 'lyricist_id', 'song_id');
    }
}
