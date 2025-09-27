<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SocialAuthController extends Controller
{
  const TOKEN_NAME = 'token';
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // التعامل مع الـ Callback من Google
    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();

        $existingUser = User::where('google_id', $user->getId())->first();

        if ($existingUser) {
            Auth::login($existingUser);
            return response()->json(['token' => $existingUser->createToken(self::TOKEN_NAME)->plainTextToken]);
        } else {
            $newUser = User::create([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'google_id' => $user->getId(),
            ]);
            Auth::login($newUser);
            return response()->json(['token' => $newUser->createToken(self::TOKEN_NAME)->plainTextToken]);
        }
    }

    // توجيه المستخدم إلى Facebook
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    // التعامل مع الـ Callback من Facebook
    public function handleFacebookCallback()
    {
        $user = Socialite::driver('facebook')->user();

        $existingUser = User::where('facebook_id', $user->getId())->first();

        if ($existingUser) {
            Auth::login($existingUser);
            return response()->json(['token' => $existingUser->createToken(self::TOKEN_NAME)->plainTextToken]);
        } else {
            $newUser = User::create([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'facebook_id' => $user->getId(),
            ]);
            Auth::login($newUser);
            return response()->json(['token' => $newUser->createToken(self::TOKEN_NAME)->plainTextToken]);
        }
    }
}
