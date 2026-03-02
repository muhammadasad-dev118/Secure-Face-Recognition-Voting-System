<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoteController extends Controller
{
    public function index()
    {
        $candidates = Candidate::all();
        $userEmail = session('face_verified_email');
        $user = User::where('email', $userEmail)->first();

        if ($user->is_voted) {
            return view('vote_success', ['already_voted' => true]);
        }

        return view('vote', compact('candidates'));
    }

    public function vote(Request $request)
    {
        $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
        ]);

        $userEmail = session('face_verified_email');
        
        try {
            DB::transaction(function () use ($request, $userEmail) {
                // 1. Lock user for update to prevent race conditions
                $user = User::where('email', $userEmail)->lockForUpdate()->first();

                if ($user->is_voted) {
                    throw new \Exception("User has already voted.");
                }

                // 2. Atomic increment
                Candidate::where('id', $request->candidate_id)->increment('votes');

                // 3. Update user status
                $user->is_voted = true;
                $user->save();
            });

            // Clear session after successful vote
            session()->forget('face_verified_email');

            return response()->json([
                'success' => true,
                'message' => 'Your vote has been cast successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }
}
