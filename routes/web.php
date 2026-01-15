<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth/login');
});

//Middlewares
Route::group(['middleware' => 'auth'], function() {
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/notifications/read/{id}', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
Route::get('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

Route::get('/join-event/{id}', [App\Http\Controllers\MR\EventController::class, 'showJoinForm']);
Route::post('/join-event/{id}', [App\Http\Controllers\MR\EventController::class, 'submitJoinForm']);

//Privacy policy
Route::get('/privacy-policy', [App\Http\Controllers\PrivacyController::class, 'privacy_policy']);
Route::get('/delete-account', [App\Http\Controllers\PrivacyController::class, 'delete_account']);

//Delete Account
//Route::get('/account/delete/{id}', [App\Http\Controllers\DeleteAccountController::class, 'destroy']);

