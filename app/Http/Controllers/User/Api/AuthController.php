<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prahari;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $request->validate([

            'name' => 'required',
            'phone' => 'required|unique:praharis',
            'password' => 'required|min:4'

        ]);

        $prahari = Prahari::create([

            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'aadhaar' => $request->aadhaar,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'ifsc_code' => $request->ifsc_code,

        ]);

        return response()->json([

            'status' => true,
            'message' => 'Signup successful',
            'data' => $prahari

        ]);
    }

    /**
     * Step 1: Send OTP to the user's phone number.
     * OTP is returned in the response for Postman testing (no third-party SMS).
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required',
        ]);

        $prahari = Prahari::where('phone', $request->phone)->first();

        if (!$prahari) {
            return response()->json([
                'status' => false,
                'message' => 'No account found with this phone number',
            ], 404);
        }

        // Generate a random 6-digit OTP
        $otp = rand(100000, 999999);

        // Store OTP with 5-minute expiry
        $prahari->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(5),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'OTP sent successfully',
            'otp' => $otp, // Returned for Postman testing — remove in production
        ]);
    }

    /**
     * Step 2: Verify OTP and issue a Sanctum token.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'otp' => 'required|digits:6',
        ]);

        $prahari = Prahari::where('phone', $request->phone)->first();

        if (!$prahari) {
            return response()->json([
                'status' => false,
                'message' => 'No account found with this phone number',
            ], 404);
        }

        // Check if OTP matches and is not expired
        if ($prahari->otp != $request->otp) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP',
            ], 401);
        }

        if (Carbon::now()->isAfter($prahari->otp_expires_at)) {
            return response()->json([
                'status' => false,
                'message' => 'OTP has expired, please request a new one',
            ], 401);
        }

        // Clear the OTP after successful verification
        $prahari->update([
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        $token = $prahari->createToken('API TOKEN')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'token' => $token,
            'user' => $prahari,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json([

            'status' => true,
            'message' => 'Logout successful'

        ]);
    }
}
