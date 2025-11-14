<?php

use Illuminate\Support\Facades\Route;

//Manager api
Route::prefix('manager')->middleware(['ensure.token','auth:sanctum', 'manager'])->group(function () {
    //Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Api\Manager\DashboardController::class, 'dashboard']); 

    //MR management
    Route::get('/mrs', [App\Http\Controllers\Api\Manager\MRController::class, 'index']);
    Route::post('/mrs', [App\Http\Controllers\Api\Manager\MRController::class, 'store']);
    Route::patch('/mrs/{id}', [App\Http\Controllers\Api\Manager\MRController::class, 'update']);
    Route::delete('/mrs/{id}', [App\Http\Controllers\Api\Manager\MRController::class, 'destroy']);
});
