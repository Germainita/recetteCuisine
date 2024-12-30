<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Categorie::create([
            'nom' => 'salades',
        ]);
        Categorie::create([
            'nom' => 'vegetarien',
        ]);
        Categorie::create([
            'nom' => 'lunch',
        ]);
        Categorie::create([
            'nom' => 'legumineuses',
        ]);
        Categorie::create([
            'nom' => 'recette de la semaine',
        ]);
    }
}
