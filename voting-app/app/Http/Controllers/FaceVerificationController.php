<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FaceVerificationController extends Controller
{
    public function index()
    {
        return view('verify');
    }

    public function fetchFaceData(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false, 
                'message' => 'Identity not found in registry.'
            ], 404);
        }

        if ($user->is_voted) {
            return response()->json([
                'success' => false, 
                'message' => 'This identity has already cast a vote.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'face_data' => $user->face_data,
            'name' => $user->name,
            'is_voted' => $user->is_voted
        ]);
    }

    public function verified(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        session(['face_verified_email' => $request->email]);

        return response()->json(['success' => true]);
    }
}
