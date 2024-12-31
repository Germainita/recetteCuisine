<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    // Inscription 
    public function register(Request $request) {
        try {
            
            // Validation du nom de la categorie 
            $request->validate(
                // Les validation 
                [
                    'nom' => 'required|string',
                    'prenom' => 'required|string',
                    'email' => 'required|max:255|unique:users,email',
                    'password' => 'required|min:6',
                ],

                // Les messages d'erreur 
                [
                    'nom.required' => "Le nom est obligatoire",
                    'prenom.required' => "Le prenom est obligatoire",
                    'email.required' => "L'email est obligatoire",
                    'email.unique' => "Cette email existe deja",
                    'password.required' => "Le mot de passe est obligatoire",
                    'password.min' => "Le mot de passe doit être avoir au minumum 6 caracteres",
                ]
            );

            // Creation de l'utilisateur 
            $user = User::create([
                'nom' => $request->get('nom'),
                'prenom' => $request->get('prenom'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
            ]);

            
            if($user){
                $token = JWTAuth::fromUser($user);
                return response()->json([
                    'success' => true,
                    'message' => 'compte cree avec succes',
                    'user' => $user,
                    'token' => $token,
                ], 201);
            }else{
                return $this->errorResponse('Erreur lors de l\'ajout');
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
    // Connexion 
    public function login(Request $request)  {
        $credentials = $request->only('email', 'password');
        try {
            if(!$token = JWTAuth::attempt($credentials) ){
                return response()->json(['error' => 'Informations incorrectes'], 401);
            }
            // On recuperer l'utilisateur authentifiee 
            $user = Auth::user(); 
            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => $user,
            ], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Impossible de creer le token'], 500);
            //throw $th;
        }
    }  
    
    // Utilisateur connecté
    public function getUser() {
        try {
            if(!$user = JWTAuth::parseToken()->authenticate()){
                return response()->json([
                    'error'=>'Utilisateur introuvable',
                ], 404);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Invalid token'], 400);
        }
        return response()->json(compact('user'));
    }

    // Deconnexion 
    public function logout()  {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Deconnexion reussie']);
    }
    
}
