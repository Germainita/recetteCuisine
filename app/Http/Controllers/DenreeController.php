<?php

namespace App\Http\Controllers;

use App\Models\Denree;
use App\Models\Recette;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DenreeController extends Controller
{

    //Recuperer la liste des denrees 
    public function index() {
        $denrees = Denree::all();
        if($denrees->isEmpty()){
            return $this->emptyResponse('La liste des denrees est vide');
        }
        return $this->successResponse($denrees);
    }

    // Recuperer une denree 
    public function show($id) {
        try {
            
            $denree= Denree::find($id);
            if(!$denree){
                return $this->errorResponse('denree non trouvée');
            }else{
                return $this->successResponse($denree, 'denree trouvée avec success');
            }
        } catch (Exception $e) {
            return response()->json([
                'success' =>false,
                'errors' => response()->json($e)
            ]);
        }
    }

    // Ajouter une denree 
    public function store(Request $request) {
        try {
            
            // Validation du nom de la categorie 
            $request->validate(
                // Les validation 
                [
                    'denree' => 'required|max:255|unique:denrees,denree',
                    'preparation' => ['required', 'regex:/^\d+$/'],
                ],

                // Les messages d'erreur 
                [
                    'denree.required' => "Le denree est obligatoire",
                    'denree.unique' => "Cette denree existe deja",
                    'preparation.required' => "Le temps de prepration est obligatoire",
                    'preparation.regex' => "Le temps de préparation doit être un entier positif",
                ]
            );

            // On recherche la recette 
            $recette = Recette::find($request->recette_id);
            if(!$recette){
                return $this->errorResponse('recette non trouvée');
            }else{
                $denree = new Denree();
                $denree->denree = $request->denree;
                $denree->preparation = $request->preparation;
                $denree->recette_id = $recette->id;
                // dd($denree);
                if($denree->save()){
                    return $this->successResponse($denree, 'denree ajoutée avec success');
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
            return response()->json($e);
        }

        
        
    }
    
    // Modifiee une denree 
    public function update(Request $request, $id) {
        try {
            
            // Validation du nom de la categorie 
            $request->validate(
                // Les validation 
                [
                    'denree' => ['required', 'max:255', 'unique:denrees,denree,' . $id],
                    'preparation' => ['required', 'regex:/^\d+$/'],
                ],

                // Les messages d'erreur 
                [
                    'denree.required' => "Le denree est obligatoire",
                    'denree.unique' => "Cette denree existe deja",
                    'preparation.required' => "Le temps de prepration est obligatoire",
                    'preparation.regex' => "Le temps de préparation doit être un entier positif",
                ]
            );

            // On recherche la recette 
            $recette = Recette::find($request->recette_id);
            if(!$recette){
                return $this->errorResponse('recette non trouvée');
            }else{
                $denree = Denree::find($id);
                if($denree){
                    $denree->denree = $request->denree;
                    $denree->preparation = $request->preparation;
                    $denree->recette_id = $recette->id;
                    // dd($denree);
                    if($denree->update()){
                        return $this->successResponse($denree, 'denree modifiée avec success');
                    }else{
                        return $this->errorResponse('Erreur lors de la modification');
                    }
                }else{
                    return $this->errorResponse('La denree que vous voulez modifier n\'existe pas ');
                }
            }

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch(Exception $e){
            return response()->json($e);
        }

        
        
    }
    

    // Supprimer une denree 
    public function destroy($id)  {
        try {
            $denree = Denree::find($id);
            if(!$denree){
                return $this->errorResponse('La denree que vous voulez supprimée n\'existe pas');
            }else{
                if($denree->delete()){
                    return response()->json([
                        'success' => true,
                        'message' => 'denree suppimee avec succes',
                    ], 200);
                }else{
                    return $this->errorResponse('Erreur lors de la suppression');
                }
            }
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
}
