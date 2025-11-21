<?php

use Illuminate\Support\Facades\Route;

//Manager api
Route::prefix('manager')->middleware(['ensure.token', 'auth:sanctum', 'manager'])->group(function () {
    //Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Api\Manager\DashboardController::class, 'dashboard']); 
    //Attendance
    Route::get('/attendance/{type?}', [App\Http\Controllers\Api\Manager\AttendanceController::class, 'index']);
    //Calendar
    Route::get('/calendar/{type?}', [App\Http\Controllers\Api\Manager\CalendarController::class, 'calendar']);
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
    //ReferredPatients
    Route::get('/referred-patients', [App\Http\Controllers\Api\Manager\PatientController::class, 'index']);
    Route::post('/referred-patient-approve/{id}', [App\Http\Controllers\Api\Manager\PatientController::class, 'approve_patient']);
    Route::post('/referred-patient-reject/{id}', [App\Http\Controllers\Api\Manager\PatientController::class, 'reject_patient']);
    Route::post('/referred-patient/{id}', [App\Http\Controllers\Api\Manager\PatientController::class, 'update']);
    Route::delete('/referred-patient/{id}', [App\Http\Controllers\Api\Manager\PatientController::class, 'destroy']);
    //Clients
    Route::get('/clients', [App\Http\Controllers\Api\Manager\ClientController::class, 'index']);
    Route::post('/client/approve/{id}', [App\Http\Controllers\Api\Manager\ClientController::class, 'approve']);
    Route::post('/client/reject/{id}', [App\Http\Controllers\Api\Manager\ClientController::class, 'reject']);
    Route::post('/client/{id}', [App\Http\Controllers\Api\Manager\ClientController::class, 'update']);
    Route::delete('/client/{id}', [App\Http\Controllers\Api\Manager\ClientController::class, 'destroy']);
    //VisitPlans
    Route::post('/visit-plan', [App\Http\Controllers\Api\Manager\VisitPlanController::class, 'store']);
    Route::get('/visit-plans', [App\Http\Controllers\Api\Manager\VisitPlanController::class, 'index']);
    Route::post('/visit-plan/status/{id}', [App\Http\Controllers\Api\Manager\VisitPlanController::class, 'updateStatus']);
    Route::post('/visit-plan/comment', [App\Http\Controllers\Api\Manager\VisitPlanController::class, 'addComment']);
    Route::post('/visit-plan/{id}', [App\Http\Controllers\Api\Manager\VisitPlanController::class, 'update']);
    Route::delete('/visit-plan/{id}', [App\Http\Controllers\Api\Manager\VisitPlanController::class, 'destroy']);
    Route::get('/visit-plans/interested', [App\Http\Controllers\Api\Manager\VisitPlanController::class, 'interestedMRS']);
    Route::post('/visit-plan/interest/{id}', [App\Http\Controllers\Api\Manager\VisitPlanController::class, 'approveInterest']);
    //TourPlans
    Route::get('/tour-plans', [App\Http\Controllers\Api\Manager\TourPlanController::class, 'index']);
    Route::post('/tour-plan/{id}', [App\Http\Controllers\Api\Manager\TourPlanController::class, 'update']);
    Route::post('/tour-plan/approve/{id}', [App\Http\Controllers\Api\Manager\TourPlanController::class, 'approve_tour_plan']);
    Route::post('/tour-plan/reject/{id}', [App\Http\Controllers\Api\Manager\TourPlanController::class, 'reject_tour_plan']);
    
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

    //Daily MR reports
    Route::prefix('daily-reports')->group(function () {
        Route::get('/', [App\Http\Controllers\Api\Manager\MRDailyReportController::class, 'index']);
        Route::get('/export', [App\Http\Controllers\Api\Manager\MRDailyReportController::class, 'export']);
        Route::post('/{id}', [App\Http\Controllers\Api\Manager\MRDailyReportController::class, 'update']);
        Route::post('/approve/{id}', [App\Http\Controllers\Api\Manager\MRDailyReportController::class, 'approve']);
        Route::post('/reject/{id}', [App\Http\Controllers\Api\Manager\MRDailyReportController::class, 'reject']);
    });
});
