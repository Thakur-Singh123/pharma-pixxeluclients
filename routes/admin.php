<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth','admin'])->group(function () {
  Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'dashboard']);
});

