<?php

use App\Http\Controllers\CreateUserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });




Route::get('/auth/github/callback', [CreateUserController::class, 'handleGithubCallback']);

Route::get('/auth/google/callback', [CreateUserController::class, 'handleGoogleCallback']);
