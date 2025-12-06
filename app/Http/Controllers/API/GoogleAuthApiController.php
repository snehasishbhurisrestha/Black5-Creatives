<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class GoogleAuthApiController extends Controller
{
    // Step 1: Redirect to Google
    public function redirectToGoogle()
    {
        $url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();

        return apiResponse(true,'Google login URL generated',['url'=>$url],200);
    }

    // Step 2: Handle Google callback
    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $nameParts = explode(' ', $googleUser->getName(), 2);
        // Find or create user
        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'first_name' => $nameParts[0],
                'last_name' => $nameParts[1] ?? '',
                'google_id' => $googleUser->getId(),
                // 'avatar' => $googleUser->getAvatar(),
                'user_id' => generateUniqueId('user'),
            ]
        );

        $user->syncRoles('User');

        // Generate JWT / Sanctum / Passport token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Instead of redirecting to a blade view, redirect to React frontend with token
        // return redirect(env('FRONTEND_URL') . '/auth/callback?token=' . $token);

        // $data = [
        //     'token' => $token,
        //     'user' => $user
        // ];

        // return apiResponse(true,'Login successfull',$data,200);

        return redirect()->away("https://black5creatives.in/google-auth-handler?token={$token}");
    }
}
