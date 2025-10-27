<?php

use Illuminate\Support\Facades\Route;

//Admin api
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    //Dashboard 
    Route::get('/admin/dashboard', [App\Http\Controllers\Api\Admin\DashboardController::class, 'dashboard']); 
});
