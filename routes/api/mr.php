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
    Route::get('/attendance', [App\Http\Controllers\Api\MR\AttendanceController::class, 'index']);
    Route::post('/attendance/check-in', [App\Http\Controllers\Api\MR\AttendanceController::class, 'checkIn']);  
    Route::post('/attendance/check-out', [App\Http\Controllers\Api\MR\AttendanceController::class, 'checkOut']);
    Route::get('/attendance/month', [App\Http\Controllers\Api\MR\AttendanceController::class, 'month']); 
    //Calander
    Route::get('/calendar-tasks', [App\Http\Controllers\Api\MR\CalendarController::class, 'all_tasks']); 
    Route::get('/calendar-events', [App\Http\Controllers\Api\MR\CalendarController::class, 'all_events']);  
});
