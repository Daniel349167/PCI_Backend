<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
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
        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'password' => bcrypt(Str::random(24))
            ]
        );

        auth()->login($user, true);

        return response()->json([
            'message' => 'User authenticated',
            'user' => $user
        ]);

    } catch (\Exception $e) {
        Log::error('Authentication failed: ' . $e->getMessage()); // Log para depuración
        return response()->json(['error' => 'Authentication failed'], 401);
    }
}

}
