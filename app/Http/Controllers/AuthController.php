<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Login user and return token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * Logout user (revoke token).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * Get authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    public function upgradePlan(Request $request)
    {
        $request->validate(['plan' => 'required|string']);
        $user = $request->user();
        
        // Mocking upgrade logic
        // In real app: Verify payment -> Update User
        $user->forceFill([
            'plan' => $request->plan,
            // Assuming 'subscription_end_date' column exists, else ignored
        ])->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Plan upgraded successfully to ' . $request->plan,
            'user' => $user
        ]);
    }

    // --- OTP & Forgot Password Logic ---
    
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $email = $request->email;
        
        // Ensure user exists (for forgot password) - but user said "signup" too.
        // If signup, user might not exist yet. However, for "Forgot Password", user must exist.
        // If user wants verification during signup, we do it after register.
        // For now, assuming "Send OTP" is primarily for Forgot Password or verification.
        
        $otp = rand(100000, 999999);
        \Illuminate\Support\Facades\Cache::put('otp_' . $email, $otp, 600); // 10 minutes

        // Send Email
        try {
            \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\GenericEmail(
                'Verification Code',
                "Your OTP code is: <b>$otp</b>. It expires in 10 minutes."
            ));
            return response()->json(['success' => true, 'message' => 'OTP sent']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send OTP'], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric'
        ]);

        $cachedOtp = \Illuminate\Support\Facades\Cache::get('otp_' . $request->email);

        if ($cachedOtp && $cachedOtp == $request->otp) {
            return response()->json(['success' => true, 'message' => 'OTP verified']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid OTP'], 400);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required',
            'password' => 'required|confirmed|min:8'
        ]);

        // Re-verify OTP to be safe
        $cachedOtp = \Illuminate\Support\Facades\Cache::get('otp_' . $request->email);
        if (!$cachedOtp || $cachedOtp != $request->otp) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired OTP'], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        \Illuminate\Support\Facades\Cache::forget('otp_' . $request->email);

        return response()->json(['success' => true, 'message' => 'Password reset successfully']);
    }
}
}
