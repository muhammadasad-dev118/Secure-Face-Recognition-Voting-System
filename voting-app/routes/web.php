<?php

use App\Http\Controllers\FaceRegistrationController;
use App\Http\Controllers\FaceVerificationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [FaceRegistrationController::class, 'index']);
Route::post('/register', [FaceRegistrationController::class, 'register']);

Route::get('/verify', [FaceVerificationController::class, 'index']);
Route::post('/fetch-face-data', [FaceVerificationController::class, 'fetchFaceData']);
Route::post('/verified', [FaceVerificationController::class, 'verified']);

Route::middleware(['face.verified'])->group(function () {
    Route::get('/vote', [\App\Http\Controllers\VoteController::class, 'index']);
    Route::post('/vote', [\App\Http\Controllers\VoteController::class, 'vote']);
    Route::get('/vote/success', function () {
        return view('vote_success');
    });
});


