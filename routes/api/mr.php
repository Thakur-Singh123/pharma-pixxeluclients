<?php

use Illuminate\Support\Facades\Route;

//Mr Api's
Route::prefix('mr')->middleware(['auth:sanctum', 'mr'])->group(function () {
    //Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Api\MR\DashboardController::class, 'dashboard']);

    //Attendances
    Route::get('/attendance/{type?}', [App\Http\Controllers\Api\MR\AttendanceController::class, 'index']);
    Route::post('/attendance', [App\Http\Controllers\Api\MR\AttendanceController::class, 'mark']);
    //Calander
    Route::get('/calendar/{type?}', [App\Http\Controllers\Api\MR\CalendarController::class, 'calendar']);
    //TADA
    Route::get('/tada', [App\Http\Controllers\Api\MR\TADAController::class, 'index']);
    Route::post('/tada', [App\Http\Controllers\Api\MR\TADAController::class, 'store']);
    Route::post('/tada/{id}', [App\Http\Controllers\Api\MR\TADAController::class, 'update']);
    Route::delete('/tada/{id}', [App\Http\Controllers\Api\MR\TADAController::class, 'destroy']);
});
