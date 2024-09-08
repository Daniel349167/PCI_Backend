<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SocialiteController extends Controller
{
    /**
     * Método opcional si estás usando flujo web
     * Este redirige al usuario a la autenticación de Google en el navegador.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Método para manejar el callback con el idToken que viene desde la app móvil (Cordova).
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            // Obtener el idToken que se envía desde el frontend
            $idToken = $request->input('idToken');
    
            // Verificar el idToken con la API de Google
            $client = new \Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]); // Client ID de Google
            $payload = $client->verifyIdToken($idToken); // Verificar token
    
            if ($payload) {
                // Extraer datos del token
                $googleUserId = $payload['sub'];  // ID único del usuario en Google
                $email = $payload['email'];
                $name = $payload['name'];
    
                // Buscar o crear un usuario en la base de datos local
                $user = User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name' => $name,
                        'google_id' => $googleUserId,
                        'password' => bcrypt(Str::random(24)) // Generar contraseña aleatoria para nuevos usuarios
                    ]
                );
    
                // Autenticar al usuario localmente
                Auth::login($user, true);
    
                // Generar token de acceso si la app usa tokens de API (Laravel Passport o Sanctum)
                $token = $user->createToken('GoogleAuthToken')->plainTextToken;
    
                // Devolver la respuesta al frontend con el token generado
                return response()->json([
                    'message' => 'User authenticated',
                    'user' => $user,
                    'token' => $token
                ]);
            } else {
                // Si el token no es válido, devolver error
                return response()->json(['error' => 'Invalid token'], 401);
            }
    
        } catch (\Exception $e) {
            // Manejar errores y loguear detalles
            Log::error('Authentication failed: ' . $e->getMessage());
            return response()->json(['error' => 'Authentication failed'], 401);
        }
    }
}

