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
//Expire token
Route::post('/expire-token', [App\Http\Controllers\Api\Auth\LoginController::class, 'expire_token']);
//Forgot password
Route::post('/forgot-password', [App\Http\Controllers\Api\Auth\LoginController::class, 'forgot']);
Route::post('/reset-password', [App\Http\Controllers\Api\Auth\LoginController::class, 'reset']);

//Profile
Route::middleware(['ensure.token','auth:sanctum'])->group(function () {
  //Notification
  Route::get('/notifications', [App\Http\Controllers\Api\MobileNotificationController::class, 'list']);
  Route::get('/notification/{id}', [App\Http\Controllers\Api\MobileNotificationController::class, 'read']);
  Route::get('/notification', [App\Http\Controllers\Api\MobileNotificationController::class, 'readAll']);
  //Update account
  Route::post('/update-account', [App\Http\Controllers\Api\ProfileController::class, 'update']);
  Route::post('/change-password', [App\Http\Controllers\Api\ProfileController::class, 'change_password']);
  //Delete account
  Route::post('/delete-account', [App\Http\Controllers\Api\ProfileController::class, 'deleteAccount']);
});
