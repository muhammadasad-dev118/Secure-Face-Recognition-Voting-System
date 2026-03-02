<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FaceVerified
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('face_verified_email')) {
            return redirect('/verify')->with('error', 'Please verify your identity first.');
        }

        return $next($request);
    }
}
