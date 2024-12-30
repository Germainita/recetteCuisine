<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    //
    protected $fillable = [
        'nom'
    ];

    // Une categorie peut appartenir a plusieurs recettes 
    public function Recette() {
        return $this->hasMany(Recette::class);
    }
}
