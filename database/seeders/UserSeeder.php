<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nom' => 'Diouf',
            'prenom' => 'Germaine',
            'email' => 'germaine@gmail.com',
            'password' => Hash::make('passer'), 
        ]);
        User::create(
            [
                'nom' => 'Diouf',
                'prenom' => 'Jacki',
                'email' => 'Jacki@gmail.com',
                'password' => Hash::make('passer'),
            ],
        );
        User::create([
            'nom' => 'Diouf',
            'prenom' => 'Monte',
            'email' => 'Monte@gmail.com',
            'password' => Hash::make('passer'),
        ]);
        User::create([
            'nom' => 'Diouf',
            'prenom' => 'daba',
            'email' => 'daba@gmail.com',
            'password' => Hash::make('passer'),
        ]);
    }
}
