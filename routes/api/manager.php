<?php

use Illuminate\Support\Facades\Route;

//Manager api
Route::middleware(['auth:sanctum', 'manager'])->group(function () {
    //Dashboard
    Route::get('/manager/dashboard', [App\Http\Controllers\Api\Manager\DashboardController::class, 'dashboard']); 
});
