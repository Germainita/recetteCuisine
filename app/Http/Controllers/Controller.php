<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

abstract class Controller
{
    public function successResponse($data, $message=null)  {
        $code = Response::HTTP_OK;
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);        
    }

    public function errorResponse($message){
        $code = Response::HTTP_BAD_REQUEST;
        return response()->json([
            'success'=>false,
            'message'=>$message
        ], $code);
    }

    public function emptyResponse($message){
        $code = Response::HTTP_NO_CONTENT;
        return response()->json([
            'status'=>204,
            'message'=>$message
        ]);
    }
}
