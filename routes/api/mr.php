<?php

use Illuminate\Support\Facades\Route;

//Mr api
Route::middleware(['auth:sanctum', 'mr'])->group(function () {
    //Dashboard
    Route::get('/mr/dashboard', [App\Http\Controllers\Api\MR\DashboardController::class, 'dashboard']); 
});
