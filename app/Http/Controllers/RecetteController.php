<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Recette;
use App\Models\User;
use Illuminate\Http\Request;
// use App\Traits\ApiResponseTrait as ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RecetteController extends Controller
{
    // use ApiResponseTrait;

    //Recuperer la liste des recettes 
    public function index() {
        // $recettes = Recette::all();
        $recettes = Recette::with('denree')->get();
        if($recettes->isEmpty()){
            return $this->emptyResponse('La liste des recettes est vide');
        }
        return $this->successResponse($recettes);
    }

    // Recuperer une recette 
    public function show($id) {
        try {
            
            $recette= Recette::with('denree')->find($id);
            if(!$recette){
                return $this->errorResponse('recette non trouvée');
            }else{
                return $this->successResponse($recette, 'recette trouvée avec success');
            }
        } catch (Exception $e) {
            return response()->json([
                'success' =>false,
                'errors' => response()->json($e)
            ]);
        }
    }

    // Ajouter une recette 
    public function store(Request $request) {
        try {
            
            // Validation du nom de la categorie 
            $request->validate(
                // Les validation 
                [
                    'titre' => 'required|max:255|unique:recettes,titre',
                    'temps_preparation' => ['required', 'regex:/^\d+$/'],
                    'temps_cuisson' => ['required', 'regex:/^\d+$/'],
                    'etape_preparation' => 'required|max:255',
                    'categorie_id' => 'required',
                ],

                // Les messages d'erreur 
                [
                    'titre.required' => "Le titre est obligatoire",
                    'titre.unique' => "Cette recette existe deja",
                    'temps_preparation.required' => "Le temps de prepration est obligatoire",
                    'temps_preparation.regex' => "Le temps de préparation doit être un entier positif",
                    'temps_cuisson.required' => "Le temps de cuisson est obligatoire",
                    'temps_cuisson.regex' => "Le temps de cuisson doit être un entier positif",
                    'etape_preparation.required' => "L'etape de preparation est obligatoire",
                    'categorie_id.required' => "La categorie_id est obligatoire",
                ]
            );

            // Recuperation de l'utilisateur connecté 
            $user = Auth::user() ;

            $categorie = Categorie::find($request->categorie_id);
            if(!$categorie){
                return $this->errorResponse('categorie non trouvée');
            }else{
                $recette = new Recette();
                $recette->titre = $request->titre;
                $recette->description = $request->description;
                $recette->temps_preparation = $request->temps_preparation;
                $recette->temps_cuisson = $request->temps_cuisson;
                $recette->etape_preparation = $request->etape_preparation;
                $recette->categorie_id = $categorie->id;
                $recette->user_id = $user->id;
                // dd($recette);
                if($recette->save()){
                    return $this->successResponse($recette, 'recette ajoutée avec success');
                }else{
                    return $this->errorResponse('Erreur lors de l\'ajout');
                }
            }

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch(Exception $e){
            return response()->json([
                'success' =>false,
                'errors' => response()->json($e)
            ]);
        }

        
        
    }

    // modifier une recette 
    public function update(Request $request, $id) {
        try {
            
            // Validation du nom de la categorie 
            $request->validate(
                // Les validation 
                [
                    'titre' => ['required', 'max:255', 'unique:recettes,titre,' . $id],
                    'temps_preparation' => ['required', 'regex:/^\d+$/'],
                    'temps_cuisson' => ['required', 'regex:/^\d+$/'],
                    'etape_preparation' => 'required|max:255',
                    'categorie_id' => 'required',
                ],

                // Les messages d'erreur 
                [
                    'titre.required' => "Le titre est obligatoire",
                    'titre.unique' => "Cette recette existe deja",
                    'temps_preparation.required' => "Le temps de prepration est obligatoire",
                    'temps_preparation.regex' => "Le temps de préparation doit être un entier positif",
                    'temps_cuisson.required' => "Le temps de cuisson est obligatoire",
                    'temps_cuisson.regex' => "Le temps de cuisson doit être un entier positif",
                    'etape_preparation.required' => "L'etape de preparation est obligatoire",
                    'categorie_id.required' => "La categorie_id est obligatoire",
                ]
            );

            // Recuperation de l'utilisateur connecté 
            $user = Auth::user() ;

            $categorie = Categorie::find($request->categorie_id);
            if(!$categorie){
                return $this->errorResponse('categorie non trouvée');
            }else{
                $recette = Recette::find($id);
                if($recette){
                    $recette->titre = $request->titre;
                    $recette->description = $request->description;
                    $recette->temps_preparation = $request->temps_preparation;
                    $recette->temps_cuisson = $request->temps_cuisson;
                    $recette->etape_preparation = $request->etape_preparation;
                    $recette->categorie_id = $categorie->id;
                    $recette->user_id = $user->id;
                    // dd($recette);
                    if($recette->update()){
                        return $this->successResponse($recette, 'recette modifiée avec success');
                    }else{
                        return $this->errorResponse('Erreur lors de l\'ajout');
                    }

                }else{
                    return $this->errorResponse('Le recette que vous essayez de modifier est introuvable');
                }
            }

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch(Exception $e){
            return response()->json([
                'success' =>false,
                'errors' => response()->json($e)
            ]);
        }

        
        
    }

    // Supprimer une recette 
    public function destroy($id)  {
        try {
            $recette = Recette::find($id);
            if(!$recette){
                return $this->errorResponse('La recette que vous voulez supprimée n\'existe pas');
            }else{
                if($recette->delete()){
                    return response()->json([
                        'success' => true,
                        'message' => 'recette suppimee avec succes',
                    ], 200);
                }else{
                    return $this->errorResponse('Erreur lors de la suppression');
                }
            }
        } catch (Exception $e) {
            return response()->json([
                'success' =>false,
                'errors' => response()->json($e)
            ]);
        }
    }
}
