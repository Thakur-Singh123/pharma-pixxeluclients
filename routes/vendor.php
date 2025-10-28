<?php

use Illuminate\Support\Facades\Route;

Route::prefix('vendor')->name('vendor.')->middleware(['auth','vendor'])->group(function () {
  Route::get('/dashboard', [App\Http\Controllers\Vendor\DashboardController::class, 'dashboard']);
});

