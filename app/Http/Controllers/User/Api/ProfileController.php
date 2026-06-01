<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function profile()
    {
        return response()->json([

            'status' => true,
            'data' => Auth::user()

        ]);
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->update([

            'name' => $request->name,
            'phone' => $request->phone

        ]);

        return response()->json([

            'status' => true,
            'message' => 'Profile updated successfully'

        ]);
    }

    public function updateSettings(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->update([

            'language' => $request->language,
            'notifications' => $request->notifications

        ]);

        return response()->json([

            'status' => true,
            'message' => 'Settings updated successfully'

        ]);
    }
}
