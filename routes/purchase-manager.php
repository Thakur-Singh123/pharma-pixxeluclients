<?php

use Illuminate\Support\Facades\Route;

//PurchaseManager Route
Route::prefix('purchase-manager')->name('purchase-manager.')->middleware(['web','auth','purchase-manager'])->group(function () {
  //Profile
  Route::get('/profile', [App\Http\Controllers\PurchaseManager\ProfileController::class, 'profile']); 
  Route::get('/edit-profile', [App\Http\Controllers\PurchaseManager\ProfileController::class, 'edit_profile']);
  Route::post('/update-profile/{id}', [App\Http\Controllers\PurchaseManager\ProfileController::class, 'update_profile'])->name('update.profile');
  Route::get('/change-password', [App\Http\Controllers\PurchaseManager\ProfileController::class, 'change_password']);
  Route::post('/submit-password/{id}', [App\Http\Controllers\PurchaseManager\ProfileController::class, 'submit_change_password'])->name('submit.change.password');
  //Dashboard
  Route::get('/dashboard', [App\Http\Controllers\PurchaseManager\DashboardController::class, 'dashboard']);
});

