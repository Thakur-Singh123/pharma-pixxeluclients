<?php

use Illuminate\Support\Facades\Route;

Route::prefix('counselor')->name('counselor.')->middleware(['web','auth','counselor'])->group(function () {
  //Profile
  Route::get('/profile', [App\Http\Controllers\Counselor\ProfileController::class, 'profile']); 
  Route::get('/edit-profile', [App\Http\Controllers\Counselor\ProfileController::class, 'edit_profile']);
  Route::post('/update-profile/{id}', [App\Http\Controllers\Counselor\ProfileController::class, 'update_profile'])->name('update.profile');
  Route::get('/change-password', [App\Http\Controllers\Counselor\ProfileController::class, 'change_password']);
  Route::post('/submit-password/{id}', [App\Http\Controllers\Counselor\ProfileController::class, 'submit_change_password'])->name('submit.change.password');
  //Dashboards
  Route::get('/dashboard', [App\Http\Controllers\Counselor\DashboardController::class, 'dashboard']);
});

