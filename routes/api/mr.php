<?php

use Illuminate\Support\Facades\Route;

//Mr Api's
Route::prefix('mr')->middleware(['auth:sanctum', 'mr'])->group(function () {
    //Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Api\MR\DashboardController::class, 'dashboard']);
    //Profile
    Route::get('/account', [App\Http\Controllers\Api\MR\ProfileController::class, 'account']);
    Route::post('/update-account', [App\Http\Controllers\Api\MR\ProfileController::class, 'update_account']);
    //Attendances
    Route::get('/attendance/{type?}', [App\Http\Controllers\Api\MR\AttendanceController::class, 'index']);
    Route::post('/attendance', [App\Http\Controllers\Api\MR\AttendanceController::class, 'mark']);
    //Calander
    Route::get('/calendar/{type?}', [App\Http\Controllers\Api\MR\CalendarController::class, 'calendar']);

});
