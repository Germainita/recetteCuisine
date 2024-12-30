<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\DenreeController;
use App\Http\Controllers\RecetteController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Authentification 
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// La liste des utilisateur 
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);

// La liste des categories 
Route::get('/categories', [CategorieController::class, 'index']);
Route::get('/categories/{id}', [CategorieController::class, 'show']);

// La liste des recettes 
Route::get('/recettes', [RecetteController::class, 'index']);
Route::get('/recettes/{id}', [RecetteController::class, 'show']);

// La liste des denree 
Route::get('/denrees', [DenreeController::class, 'index']);
Route::get('/denrees/{id}', [DenreeController::class, 'show']);



// Les endpoints avec middleware
Route::middleware([JwtMiddleware::class])->group(function () {
    Route::get('/user', [AuthController::class, 'getUser']); //Recup utilisateur connecté
    Route::get('/logout', [AuthController::class, 'logout']); //Deconnexion

    // Gestion categorie 
    Route::post('/categorie', [CategorieController::class, 'store']); //Ajout
    Route::put('/categorie/{id}', [CategorieController::class, 'update']); //Modification
    Route::delete('/categorie/{id}', [CategorieController::class, 'destroy']); //suppression
    
    // Gestion recette 
    Route::post('/recette', [RecetteController::class, 'store']); //Ajout
    Route::put('/recette/{id}', [RecetteController::class, 'update']); //Modification
    Route::delete('/recette/{id}', [RecetteController::class, 'destroy']); //suppression
    
    // Gestion denree 
    Route::post('/denree', [DenreeController::class, 'store']); //Ajout
    Route::put('/denree/{id}', [DenreeController::class, 'update']); //Modification
    Route::delete('/denree/{id}', [DenreeController::class, 'destroy']); //suppression
});
