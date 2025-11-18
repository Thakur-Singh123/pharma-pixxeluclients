<?php

use Illuminate\Support\Facades\Route;

//Manager api
Route::prefix('manager')->middleware(['ensure.token', 'auth:sanctum', 'manager'])->group(function () {
    //Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Api\Manager\DashboardController::class, 'dashboard']); 
    //Attendance
    Route::get('/attendance/{type?}', [App\Http\Controllers\Api\Manager\AttendanceController::class, 'index']);

    //MR management
    Route::get('/mrs', [App\Http\Controllers\Api\Manager\MRController::class, 'index']);
    Route::post('/mrs', [App\Http\Controllers\Api\Manager\MRController::class, 'store']);
    Route::patch('/mrs/{id}', [App\Http\Controllers\Api\Manager\MRController::class, 'update']);
    Route::delete('/mrs/{id}', [App\Http\Controllers\Api\Manager\MRController::class, 'destroy']);

    //User status management
    Route::get('/users', [App\Http\Controllers\Api\Manager\UserStatusController::class, 'index']);
    Route::post('/users/approve/{id}', [App\Http\Controllers\Api\Manager\UserStatusController::class, 'approve']);
    Route::post('/users/suspend/{id}', [App\Http\Controllers\Api\Manager\UserStatusController::class, 'suspend']);

    //Common listings
    Route::get('/mrs', [App\Http\Controllers\Api\Manager\CommonController::class, 'mrListing']);
    Route::get('/doctors', [App\Http\Controllers\Api\Manager\CommonController::class, 'doctorListing']);

    //Event management
    Route::prefix('events')->group(function () {
        Route::get('/', [App\Http\Controllers\Api\Manager\EventController::class, 'index']);
        Route::get('/waiting-for-approval', [App\Http\Controllers\Api\Manager\EventController::class, 'pendingForApproval']);
        Route::get('/participations', [App\Http\Controllers\Api\Manager\EventController::class, 'participations']);
        Route::post('/', [App\Http\Controllers\Api\Manager\EventController::class, 'store']);
        Route::post('/{id}', [App\Http\Controllers\Api\Manager\EventController::class, 'update']);
        Route::delete('/{id}', [App\Http\Controllers\Api\Manager\EventController::class, 'destroy']);
        Route::post('/status/{id}', [App\Http\Controllers\Api\Manager\EventController::class, 'updateStatus']);
        Route::post('/approve/{id}', [App\Http\Controllers\Api\Manager\EventController::class, 'approve']);
        Route::post('/reject/{id}', [App\Http\Controllers\Api\Manager\EventController::class, 'reject']);
    });
});
