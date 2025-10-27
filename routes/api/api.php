<?php

use Illuminate\Support\Facades\Route;

//Register
Route::post('/register', [App\Http\Controllers\Api\Auth\RegisterController::class, 'register']); 
//Login
Route::post('/login', [App\Http\Controllers\Api\Auth\LoginController::class, 'login']); 
//Logout
Route::middleware('auth:sanctum')->post('/logout', [App\Http\Controllers\Api\Auth\LoginController::class, 'logout']);
//Refresh token
Route::post('/refresh-token', [App\Http\Controllers\Api\Auth\LoginController::class, 'refreshToken']); 