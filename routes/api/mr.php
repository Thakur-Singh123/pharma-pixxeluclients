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
    //TADA
    Route::get('/tada', [App\Http\Controllers\Api\MR\TADAController::class, 'all_tada']);
    Route::post('/tada-create', [App\Http\Controllers\Api\MR\TADAController::class, 'create_tada']);
    Route::post('/tada-update', [App\Http\Controllers\Api\MR\TADAController::class, 'update_tada']);
    Route::post('/tada-delete', [App\Http\Controllers\Api\MR\TADAController::class, 'delete_tada']);
});
