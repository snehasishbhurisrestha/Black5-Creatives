<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;
use Illuminate\Validation\Rule;
use Validator;

class AuthenticationApiController extends Controller
{
    /**
     * Register API
     */
    /*public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return apiResponse(false, 'Validation error', $validator->errors(), 422);
        }

        $nameParts = explode(' ', $request->name, 2);

        $user = User::create([
            'name'     => $request->name,
            'first_name' => $nameParts[0],
            'last_name' => $nameParts[1] ?? '',
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->user_id = generateUniqueId('user');

        $user->syncRoles('User');

        $token = $user->createToken('authToken')->plainTextToken; 

        $data = [
            'token' => $token,
            'user'  => $user,
        ];

        return apiResponse(true, 'User registered successfully', $data, 200);
    }*/
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            // 'email'    => 'required|string|email|max:255|unique:users,email,NULL,id,is_verified,0',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        // $validator = Validator::make($request->all(), [
        //     'name'     => 'required|string|max:255',
        //     'email'    => [
        //         'required',
        //         'string',
        //         'email',
        //         'max:255',
        //         Rule::unique('users')->where(function ($query) {
        //             return $query->where('is_verified', 0);
        //         }),
        //     ],
        //     'password' => 'required|string|min:6',
        // ]);

        if ($validator->fails()) {
            return apiResponse(false, 'Validation error', $validator->errors(), 422);
        }

        // Split name
        $nameParts = explode(' ', $request->name, 2);

        // Check if unverified user already exists 
        $existingUser = User::where('email', $request->email)->first();

        if ($existingUser && !$existingUser->is_verified) {
            // Generate new OTP
            $otp = generateOTP();

            $existingUser->update([
                'otp' => $otp,
                'otp_expires_at' => now()->addMinutes(10),
            ]);

            // Send mail again
            Mail::to($existingUser->email)->send(new SendOtpMail($existingUser->name, $otp));

            return apiResponse(true, 'New OTP sent again to your email.', [
                'email' => $existingUser->email,
            ], 200);
        }

        // Create new user 
        $user = User::create([
            'name'       => $request->name,
            'first_name' => $nameParts[0],
            'last_name'  => $nameParts[1] ?? '',
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'otp'        => $otp = generateOTP(),
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        $user->user_id = generateUniqueId('user');
        $user->syncRoles('User');

        // Send OTP Email
        Mail::to($user->email)->send(new SendOtpMail($user->name, $otp));

        return apiResponse(true, 'OTP sent to your email for verification.', [
            'email' => $user->email
        ], 200);
    }


    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp'   => 'required|digits:4',
        ]);

        if ($validator->fails()) {
            return apiResponse(false, 'Validation error', $validator->errors(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return apiResponse(false, 'User not found', null, 404);
        }

        if ($user->is_verified) {
            return apiResponse(true, 'User already verified!', null, 200);
        }

        if ($user->otp !== $request->otp) {
            return apiResponse(false, 'Invalid OTP', null, 400);
        }

        if ($user->otp_expires_at < now()) {
            return apiResponse(false, 'OTP expired. Please request again.', null, 400);
        }

        // Verify user
        $user->update([
            'is_verified' => true,
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        // login token
        $token = $user->createToken('authToken')->plainTextToken; 

        return apiResponse(true, 'OTP Verified Successfully', [
            'token' => $token,
            'user' => $user
        ], 200);
    }



    /**
     * Login API
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return apiResponse(false, 'Validation error', $validator->errors(), 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return apiResponse(false, 'Invalid login credentials', null, 401);
        }

        $user  = Auth::user();
        $token = $user->createToken('authToken')->plainTextToken;

        $data = [
            'token' => $token,
            'user'  => $user
        ];
        return apiResponse(true, 'Login successful', $data, 200);
    }

    /**
     * Logout API
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return apiResponse(true, 'Logged out successfully', null, 200);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return apiResponse(false, 'Validation error', $validator->errors(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return apiResponse(false, 'User not found', null, 404);
        }

        // Generate OTP
        $otp = generateOTP();

        // Update OTP
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        // Send OTP Mail
        Mail::to($user->email)->send(new SendOtpMail($user->name, $otp));

        return apiResponse(true, 'OTP sent to your email.', [
            'email' => $user->email
        ], 200);
    }

    public function verifyForgotOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp'   => 'required|digits:4',
        ]);

        if ($validator->fails()) {
            return apiResponse(false, 'Validation error', $validator->errors(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return apiResponse(false, 'User not found', null, 404);
        }

        if ($user->otp !== $request->otp) {
            return apiResponse(false, 'Invalid OTP', null, 400);
        }

        if ($user->otp_expires_at < now()) {
            return apiResponse(false, 'OTP expired. Please request again.', null, 400);
        }

        // OTP Verified Successfully
        return apiResponse(true, 'OTP Verified. You can reset your password now.', null, 200);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'otp'      => 'required|digits:4',
            'password' => 'required|string|min:6|confirmed',
            // must send "password_confirmation"
        ]);

        if ($validator->fails()) {
            return apiResponse(false, 'Validation error', $validator->errors(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return apiResponse(false, 'User not found', null, 404);
        }

        if ($user->otp !== $request->otp) {
            return apiResponse(false, 'Invalid OTP', null, 400);
        }

        if ($user->otp_expires_at < now()) {
            return apiResponse(false, 'OTP expired.', null, 400);
        }

        // Reset password
        $user->update([
            'password' => Hash::make($request->password),
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        return apiResponse(true, 'Password reset successfully.', null, 200);
    }

    public function resendForgotOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return apiResponse(false, 'Validation error', $validator->errors(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return apiResponse(false, 'User not found', null, 404);
        }

        $otp = generateOTP();

        $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user->email)->send(new SendOtpMail($user->name, $otp));

        return apiResponse(true, 'New OTP sent.', null, 200);
    }

}
