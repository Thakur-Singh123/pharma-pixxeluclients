<?php

use Illuminate\Support\Facades\Route;

Route::prefix('purchase-manager')->name('purchase-manager.')->middleware(['auth','purchase-manager'])->group(function () {
  Route::get('/dashboard', [App\Http\Controllers\PurchaseManager\DashboardController::class, 'dashboard']);
});

