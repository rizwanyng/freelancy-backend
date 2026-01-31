<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422);
        }

        $verified = Cache::get('otp_verified_' . $request->email);
        // Bypass if verify-otp was called successfully
        if (!$verified) {
             return response()->json([
                'success' => false,
                'message' => 'Email not verified. Please verify OTP first.'
            ], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Cache::forget('otp_verified_' . $request->email);
        Cache::forget('otp_' . $request->email);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token, // Return token so frontend logs in immediately
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid login details'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login success',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $otp = rand(100000, 999999);
        Cache::put('otp_' . $request->email, $otp, 600); // 10 minutes

        // Send Email
        try {
            // Using raw email for simplicity. Ensure Mail config is set in .env
            Mail::raw("Your Verification Code is: $otp", function ($message) use ($request) {
                $message->to($request->email)->subject('Verification Code');
            });
        } catch (\Exception $e) {
             // For debugging dev environment without mail
             return response()->json([
                 'success' => false, 
                 'message' => 'Failed to send email. Check server logs.',
                 'debug_otp' => $otp // REMOVE IN PRODUCTION
             ], 500);
        }

        return response()->json(['success' => true, 'message' => 'OTP sent to email.']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email', 
            'otp' => 'required'
        ]);

        $cachedOtp = Cache::get('otp_' . $request->email);

        if ($cachedOtp && $cachedOtp == $request->otp) {
            // Mark as verified for registration
            Cache::put('otp_verified_' . $request->email, true, 3600); 
            return response()->json(['success' => true, 'message' => 'OTP Verified']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid or expired OTP'], 400);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required',
            'password' => 'required|min:8|confirmed'
        ]);

        $cachedOtp = Cache::get('otp_' . $request->email);
        
        if (!$cachedOtp || $cachedOtp != $request->otp) {
            return response()->json(['success' => false, 'message' => 'Invalid OTP'], 400);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();
        
        Cache::forget('otp_' . $request->email);

        return response()->json(['success' => true, 'message' => 'Password reset successfully']);
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }
        return response()->json(['message' => 'Logged out']);
    }

    public function user(Request $request)
    {
        return response()->json(['user' => $request->user()]);
    }
    
    public function upgradePlan(Request $request)
    {
        return response()->json(['message' => 'Upgrade successful']);
    }
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        if ($request->has("name")) $user->name = $request->name;
        if ($request->has("phone")) $user->phone = $request->phone;
        $user->save();
        return response()->json(["success" => true, "user" => $user]);
    }
}
}
