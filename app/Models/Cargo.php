<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $fillable = ['nombre'];
    
    public function registros()
    {
        return $this->hasMany(Registro::class);
    }
}
