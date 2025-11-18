<?php

use Illuminate\Support\Facades\Route;


//Manager api
Route::prefix('manager')->middleware(['ensure.token','auth:sanctum', 'manager'])->group(function () {
    //Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Api\Manager\DashboardController::class, 'dashboard']); 
    //MR management
    Route::get('/mrs', [App\Http\Controllers\Api\Manager\MRController::class, 'index']);
    Route::post('/mrs', [App\Http\Controllers\Api\Manager\MRController::class, 'store']);
    Route::post('/mrs/{id}', [App\Http\Controllers\Api\Manager\MRController::class, 'update']);
    Route::delete('/mrs/{id}', [App\Http\Controllers\Api\Manager\MRController::class, 'destroy']);
    //Doctors
    Route::get('/doctors', [App\Http\Controllers\Api\Manager\DoctorController::class, 'index']);
    Route::post('/doctors', [App\Http\Controllers\Api\Manager\DoctorController::class, 'store']);
    Route::post('/doctors/{id}', [App\Http\Controllers\Api\Manager\DoctorController::class, 'update']);
    Route::delete('/doctors/{id}', [App\Http\Controllers\Api\Manager\DoctorController::class, 'destroy']);
    //ReferredPatient
    Route::get('/referred-patients', [App\Http\Controllers\Api\Manager\PatientController::class, 'index']);
    Route::post('/referred-patient-approve/{id}', [App\Http\Controllers\Api\Manager\PatientController::class, 'approve_patient']);
    Route::post('/referred-patient-reject/{id}', [App\Http\Controllers\Api\Manager\PatientController::class, 'reject_patient']);
    Route::post('/referred-patient/{id}', [App\Http\Controllers\Api\Manager\PatientController::class, 'update']);
    Route::delete('/referred-patient/{id}', [App\Http\Controllers\Api\Manager\PatientController::class, 'destroy']);
    //TADA
    Route::get('/tada', [App\Http\Controllers\Api\Manager\TADAController::class, 'index']);
    Route::post('/tada/approve/{id}', [App\Http\Controllers\Api\Manager\TADAController::class, 'approve']);
    Route::post('/tada/reject/{id}', [App\Http\Controllers\Api\Manager\TADAController::class, 'reject']);
    Route::post('/tada/{id}', [App\Http\Controllers\Api\Manager\TADAController::class, 'update']);
    Route::delete('/tada/{id}', [App\Http\Controllers\Api\Manager\TADAController::class, 'destroy']);
    //Visit
    Route::get('/visits', [App\Http\Controllers\Api\Manager\VisitController::class, 'index']);
    Route::post('/visit/approve/{id}', [App\Http\Controllers\Api\Manager\VisitController::class, 'approve']);
    Route::post('/visit/reject/{id}', [App\Http\Controllers\Api\Manager\VisitController::class, 'reject']);
    Route::post('/visit', [App\Http\Controllers\Api\Manager\VisitController::class, 'update']);
    Route::delete('/visis', [App\Http\Controllers\Api\Manager\VisitController::class, 'destroy']);
});
