<?php

use Illuminate\Support\Facades\Route;

Route::prefix('manager')->name('manager.')->middleware(['web','auth','manager'])->group(function () {
  Route::get('/dashboard', [App\Http\Controllers\Manager\DashboardController::class, 'dashboard']);
   Route::resource('mrs', App\Http\Controllers\Manager\MRController::class);
   Route::resource('tasks', App\Http\Controllers\Manager\TaskController::class);
});

