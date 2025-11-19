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
    Route::post('/mrs/{id}', [App\Http\Controllers\Api\Manager\MRController::class, 'update']);
    Route::delete('/mrs/{id}', [App\Http\Controllers\Api\Manager\MRController::class, 'destroy']);
    //User status management
    Route::get('/users', [App\Http\Controllers\Api\Manager\UserStatusController::class, 'index']);
    Route::post('/users/approve/{id}', [App\Http\Controllers\Api\Manager\UserStatusController::class, 'approve']);
    Route::post('/users/suspend/{id}', [App\Http\Controllers\Api\Manager\UserStatusController::class, 'suspend']);
    //Visit
    Route::get('/visits', [App\Http\Controllers\Api\Manager\VisitController::class, 'index']);
    Route::post('/visit/approve/{id}', [App\Http\Controllers\Api\Manager\VisitController::class, 'approve']);
    Route::post('/visit/reject/{id}', [App\Http\Controllers\Api\Manager\VisitController::class, 'reject']);
    Route::post('/visit/{id}', [App\Http\Controllers\Api\Manager\VisitController::class, 'update']);
    Route::delete('/visit/{id}', [App\Http\Controllers\Api\Manager\VisitController::class, 'destroy']);
    //TADA
    Route::get('/tada', [App\Http\Controllers\Api\Manager\TADAController::class, 'index']);
    Route::post('/tada/approve/{id}', [App\Http\Controllers\Api\Manager\TADAController::class, 'approve']);
    Route::post('/tada/reject/{id}', [App\Http\Controllers\Api\Manager\TADAController::class, 'reject']);
    Route::post('/tada/{id}', [App\Http\Controllers\Api\Manager\TADAController::class, 'update']);
    Route::delete('/tada/{id}', [App\Http\Controllers\Api\Manager\TADAController::class, 'destroy']);
    //Doctors
    Route::get('/doctors', [App\Http\Controllers\Api\Manager\DoctorController::class, 'index']);
    Route::post('/doctors', [App\Http\Controllers\Api\Manager\DoctorController::class, 'store']);
    Route::post('/doctors/{id}', [App\Http\Controllers\Api\Manager\DoctorController::class, 'update']);
    Route::delete('/doctors/{id}', [App\Http\Controllers\Api\Manager\DoctorController::class, 'destroy']);
    //Common listings
    Route::get('/mrs', [App\Http\Controllers\Api\Manager\CommonController::class, 'mrListing']);
    Route::get('/doctors', [App\Http\Controllers\Api\Manager\CommonController::class, 'doctorListing']);
    //ReferredPatient
    Route::get('/referred-patients', [App\Http\Controllers\Api\Manager\PatientController::class, 'index']);
    Route::post('/referred-patient-approve/{id}', [App\Http\Controllers\Api\Manager\PatientController::class, 'approve_patient']);
    Route::post('/referred-patient-reject/{id}', [App\Http\Controllers\Api\Manager\PatientController::class, 'reject_patient']);
    Route::post('/referred-patient/{id}', [App\Http\Controllers\Api\Manager\PatientController::class, 'update']);
    Route::delete('/referred-patient/{id}', [App\Http\Controllers\Api\Manager\PatientController::class, 'destroy']);



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

    //Task management
    Route::prefix('tasks')->group(function () {
        Route::get('/', [App\Http\Controllers\Api\Manager\TaskController::class, 'index']);
        Route::post('/', [App\Http\Controllers\Api\Manager\TaskController::class, 'store']);
        Route::post('/status/{id}', [App\Http\Controllers\Api\Manager\TaskController::class, 'updateStatus']);
        Route::post('/approve/{id}', [App\Http\Controllers\Api\Manager\TaskController::class, 'approve']);
        Route::post('/reject/{id}', [App\Http\Controllers\Api\Manager\TaskController::class, 'reject']);
        Route::post('/{id}', [App\Http\Controllers\Api\Manager\TaskController::class, 'update']);
    });
});
