<?php

namespace App\Models\Persona;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Human extends Model
{
    use HasFactory;

    public function Persona()
    {
        return $this->hasOne(Persona::class);
    }

    public function strong(): Attribute
    {
        return Attribute::make(
            // アクセサ
            get: fn ($value) => $this->$this."/"
        );
    }
}
