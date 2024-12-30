<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Response;
use App\Traits\ApiResponseTrait as ApiResponseTrait;

class UserController extends Controller
{
    use ApiResponseTrait;

    //Recuperer la liste des utilisateur 
    
    public function index() {
        // $users =  User::all();
        $users = User::with('recette')->get();
        if($users->isEmpty()){
            return $this->emptyResponse('La liste des utilisateur est vide');
        }
        return $this->successResponse($users);
    }

    // Recuperer un utilisateur 
    public function show($id) {
        try {
            
            // $user= User::find($id);
            $user = User::with('recette')->find($id);
            if(!$user){
                return $this->errorResponse('Utiliateur non trouvé');
                // $code = Response::HTTP_BAD_REQUEST;
                // return response()->json([
                //     'success'=> false,
                //     'message' => 'Utiliateur non trouvé',                    
                // ], $code );
            }else{
                return $this->successResponse($user, 'Utilisateur trouvé avec success');
                // $code = Response::HTTP_OK;
                // return response()->json([
                //     'success'=> true,
                //     'data' => $user,                    
                // ], $code );
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
