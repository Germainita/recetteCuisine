<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Denree extends Model
{
    //
    protected $fillable = [
        'denree',
        'proportion',
        'recette_id'
    ];

    // Une denree apparttient a une recette 
    public function Denree() {
        return $this->belongsTo(Recette::class, 'recette_id');
    }
}
