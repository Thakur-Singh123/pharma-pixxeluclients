<?php

use Illuminate\Support\Facades\Route;

// ===== User Login API =====
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'user_login']); 