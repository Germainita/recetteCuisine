<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recette extends Model
{
    //
    protected $fillable = [
        'titre',
        'description',
        'temps_preparation',
        'temps_cuisson',
        'etape_preparation',
        'categorie_id',
        'user_id',
    ];

    // Pour dire que la categorie_id est une cle etrangere qui migre de la table categorie vers la table recette 
    public function Categorie(){
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    // Une recette est cree par un utilisateur 
    public function User() {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Une rectte a plusieurs denree et proportion 
    public function Denree() {
        return $this->hasMany(Denree::class);
    }
}
