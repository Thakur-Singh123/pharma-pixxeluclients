<?php

use Illuminate\Support\Facades\Route;

//Register
Route::post('/register', [App\Http\Controllers\Api\Auth\RegisterController::class, 'register']); 
//Login
Route::post('/login', [App\Http\Controllers\Api\Auth\LoginController::class, 'login']); 
//Logout
Route::post('/logout', [App\Http\Controllers\Api\Auth\LoginController::class, 'logout']); 
//Refresh token
Route::post('/refresh-token', [App\Http\Controllers\Api\Auth\LoginController::class, 'refreshToken']); 
//expire token
Route::post('/expire-token', [App\Http\Controllers\Api\Auth\LoginController::class, 'expire_token']);

//Profile
Route::middleware(['ensure.token','auth:sanctum'])->group(function () {
  Route::post('/update-account', [App\Http\Controllers\Api\ProfileController::class, 'update']);
});
