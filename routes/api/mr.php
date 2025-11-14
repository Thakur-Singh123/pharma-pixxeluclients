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
    //Events
    Route::get('/events', [App\Http\Controllers\Api\MR\EventController::class, 'index']);
    Route::get('/events/pending-approval', [App\Http\Controllers\Api\MR\EventController::class, 'pendingForApproval']);
    Route::get('/events/manager', [App\Http\Controllers\Api\MR\EventController::class, 'assign_manger']);
    Route::get('/events/self', [App\Http\Controllers\Api\MR\EventController::class, 'himself']);
    Route::get('/events/participations', [App\Http\Controllers\Api\MR\EventController::class, 'participations']);
    Route::post('/events', [App\Http\Controllers\Api\MR\EventController::class, 'store']);
    Route::post('/events/{id}', [App\Http\Controllers\Api\MR\EventController::class, 'update']);
    Route::delete('/events/{id}', [App\Http\Controllers\Api\MR\EventController::class, 'destroy']);

    //common
    Route::get('/doctor-listing', [App\Http\Controllers\Api\MR\CommonController::class, 'mr_doctor_listing']);
});
