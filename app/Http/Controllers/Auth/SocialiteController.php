<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            Log::info('Google callback hit'); // Log para depuración
            $googleUser = Socialite::driver('google')->user();
            
            // Buscar o crear un usuario basado en su correo electrónico
            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(Str::random(24))
                ]
            );

            // Autenticar al usuario
            Auth::login($user, true);

            // Generar token si estás usando API (opcional)
            $token = $user->createToken('GoogleAuthToken')->plainTextToken;

            return response()->json([
                'message' => 'User authenticated',
                'user' => $user,
                'token' => $token // Devuelve el token si es necesario
            ]);

        } catch (\Exception $e) {
            Log::error('Authentication failed: ' . $e->getMessage()); // Log para depuración
            return response()->json(['error' => 'Authentication failed'], 401);
        }
    }
}
