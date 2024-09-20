<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokenValidationController extends Controller
{
    /**
     * Método para validar el token de API (usando Laravel Sanctum o Passport)
     */
    public function validateToken(Request $request)
    {
        // Intentar autenticar al usuario a través del token de la solicitud
        if (Auth::guard('sanctum')->check()) {
            // Si el token es válido, devolver un mensaje de éxito
            return response()->json([
                'valid' => true,
                'message' => 'Token is valid',
                'user' => Auth::guard('sanctum')->user() // Puedes devolver los datos del usuario si es necesario
            ]);
        }

        // Si el token no es válido o ha expirado, devolver un error
        return response()->json([
            'valid' => false,
            'message' => 'Token is invalid or expired'
        ], 401);
    }
}
