<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Str;

class FaceRegistrationController extends Controller
{
    public function index()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'face_data' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $newDescriptor = json_decode($request->face_data, true);
            
            // Biometric Deduplication Check
            $users = User::all(['id', 'face_data', 'email']);
            foreach ($users as $user) {
                $existingDescriptor = json_decode($user->face_data, true);
                if ($existingDescriptor) {
                    $distance = $this->euclideanDistance($newDescriptor, $existingDescriptor);
                    if ($distance < 0.55) { // Strict threshold for deduplication
                        return response()->json([
                            'success' => false,
                            'message' => 'Identity already registered with another email.'
                        ], 422);
                    }
                }
            }

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Str::random(16), 
                'face_data' => $request->face_data,
                'is_voted' => false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage()
            ], 500);
        }
    }

    private function euclideanDistance($desc1, $desc2)
    {
        if (count($desc1) !== count($desc2)) return 1.0;
        $sum = 0;
        for ($i = 0; $i < count($desc1); $i++) {
            $sum += pow($desc1[$i] - $desc2[$i], 2);
        }
        return sqrt($sum);
    }
}
