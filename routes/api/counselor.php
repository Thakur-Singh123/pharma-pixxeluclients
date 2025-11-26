<?php

use Illuminate\Support\Facades\Route;


//Counselor api
Route::prefix('counselor')->middleware(['ensure.token','auth:sanctum', 'counselor'])->group(function () {
    //Dashboard 
    Route::get('/dashboard', [App\Http\Controllers\Api\Counselor\DashboardController::class, 'dashboard']);
    //Patient
    Route::get('/patients', [App\Http\Controllers\Api\Counselor\PatientController::class, 'index']);
    Route::post('/patient', [App\Http\Controllers\Api\Counselor\PatientController::class, 'store']);
    Route::post('/patient/status/{id}', [App\Http\Controllers\Api\Counselor\PatientController::class, 'updateStatus']);
    Route::post('/patient/{id}', [App\Http\Controllers\Api\Counselor\PatientController::class, 'update']);
    Route::delete('/patient/{id}', [App\Http\Controllers\Api\Counselor\PatientController::class, 'destroy']);        
});
