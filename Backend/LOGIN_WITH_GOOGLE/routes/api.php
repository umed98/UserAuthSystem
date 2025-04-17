<?php

use App\Http\Controllers\CreateUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');



//signup api
Route::post('/register', [CreateUserController::class, 'register']);


Route::post('/verify-otp', [CreateUserController::class, 'verifyOtp']);



//login api
Route::post('/login', [CreateUserController::class, 'loginuser']);


Route::get('/auth/github', [CreateUserController::class, 'redirectToGithub']);


Route::get('/auth/google', [CreateUserController::class, 'redirectToGoogle']);


