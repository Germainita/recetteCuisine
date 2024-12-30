<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait as ApiResponseTrait;
use Exception;
use Illuminate\Validation\ValidationException;

class CategorieController extends Controller
{
    use ApiResponseTrait;

    //Recuperer la liste des categories 
    public function index() {
        $categories = Categorie::all();
        if($categories->isEmpty()){
            return $this->emptyResponse('La liste des categories est vide');
        }
        return $this->successResponse($categories);
    }

    // Recuperer une categorie 
    public function show($id) {
        try {
            
            $categorie= Categorie::find($id);
            if(!$categorie){
                return $this->errorResponse('Categorie non trouvé');
            }else{
                return $this->successResponse($categorie, 'Categorie trouvé avec success');
            }
        } catch (Exception $e) {
            return response()->json([
                'success' =>false,
                'errors' => response()->json($e)
            ]);
        }
    }

    // Ajouter une categorie 
    public function store(Request $request){
        try {
            $request->validate(
                ['nom' => 'required|max:255|unique:categories,nom'],
                ['nom.unique' => "Cette categorie existe deja"]
            );

            // Verification si la categorie existe deja 
            $categorie = Categorie::create($request->only('nom'));
            if(!$categorie){
                return $this->errorResponse('Cette categories existe deja');
            }else{
                return $this->successResponse($categorie, 'Categorie ajouté avec succes');
    
            }
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' =>false,
                'errors' => response()->json($e)
            ]);
        }
    }
    
    // Modifier une categorie 
    public function update(Request $request, $id){
        try {
            
            // Validation du nom de la categorie 
            $request->validate(
                ['nom' => 'required|max:255|unique:categories,nom,' . $id,],
                ['nom.unique' => "Cette categorie existe deja"]
            );

            // Retrouver la categorie a modifier 
            $categorie = Categorie::find($id);
            // dd($categorie);
            if(!$categorie){
                return $this->errorResponse('La categorie recherchée n\'existe pas');
            }else{
                // Affecter le nom saisie a la categorie a modifier
                // dd($request->all()); 
                $categorie->nom = $request->nom;
                if($categorie->update()){
                    return $this->successResponse($categorie, 'Categorie modifié avec succes');
                }else{
                    return $this->errorResponse('Erreur lors de la modification');
                }
    
            }
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' =>false,
                'errors' => response()->json($e)
            ]);
        }
    }

    // Supprimer une categorie 
    public function destroy($id)  {
        try {
            $categorie = Categorie::find($id);
            if(!$categorie){
                return $this->errorResponse('La categorie que vous voulez supprimée n\'existe pas');
            }else{
                if($categorie->delete()){
                    return response()->json([
                        'success' => true,
                        'message' => 'Categorie suppimee avec succes',
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
