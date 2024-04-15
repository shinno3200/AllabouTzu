<?php

namespace App\Models\TSI;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $hidden = ['created_at', 'updated_at'];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function lyricists()
    {
        return $this->belongsToMany(Lyricist::class, 'song_lyricist_composer_arranger', 'song_id', 'lyricist_id');
    }

    public function composers()
    {
        return $this->belongsToMany(Composer::class, 'song_lyricist_composer_arranger', 'song_id', 'composer_id');
    }

    public function arrangers()
    {
        return $this->belongsToMany(Arranger::class, 'song_lyricist_composer_arranger', 'song_id', 'arranger_id');
    }
}
