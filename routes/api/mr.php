<?php

use Illuminate\Support\Facades\Route;

//Mr Api's
Route::prefix('mr')->middleware(['ensure.token','auth:sanctum', 'mr'])->group(function () {
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
    Route::post('/events/status/{id}', [App\Http\Controllers\Api\MR\EventController::class, 'updateStatus']);
    //Problems / challenges
    Route::get('/problems', [App\Http\Controllers\Api\MR\ProblemController::class, 'index']);
    Route::post('/problems', [App\Http\Controllers\Api\MR\ProblemController::class, 'store']);
    Route::post('/problems/{id}', [App\Http\Controllers\Api\MR\ProblemController::class, 'update']);
    Route::delete('/problems/{id}', [App\Http\Controllers\Api\MR\ProblemController::class, 'destroy']);
    //Referred patients
    Route::get('/referred-patients', [App\Http\Controllers\Api\MR\ReferredPatientController::class, 'index']);
    Route::post('/referred-patients', [App\Http\Controllers\Api\MR\ReferredPatientController::class, 'store']);
    Route::post('/referred-patients/{id}', [App\Http\Controllers\Api\MR\ReferredPatientController::class, 'update']);
    Route::delete('/referred-patients/{id}', [App\Http\Controllers\Api\MR\ReferredPatientController::class, 'destroy']);
    //common
    Route::get('/doctor-listing', [App\Http\Controllers\Api\MR\CommonController::class, 'mr_doctor_listing']);
    //visit plans
    Route::get('/visit-plans', [App\Http\Controllers\Api\MR\VisitPlanController::class, 'index']);
    Route::post('/visit-plans/interest/{id}', [App\Http\Controllers\Api\MR\VisitPlanController::class, 'expressInterest']);
    Route::get('/visit-plans/interested', [App\Http\Controllers\Api\MR\VisitPlanController::class, 'myInterestedPlans']);
    Route::get('/visit-plans/assigned', [App\Http\Controllers\Api\MR\VisitPlanController::class, 'myAssignedPlans']);
    //Daily reports
    Route::get('/daily-reports', [App\Http\Controllers\Api\MR\DailyReportController::class, 'index']);
    Route::post('/daily-report', [App\Http\Controllers\Api\MR\DailyReportController::class, 'store']);
    Route::post('/daily-report-update/{id}', [App\Http\Controllers\Api\MR\DailyReportController::class, 'update']);
    Route::delete('/daily-report/{id}', [App\Http\Controllers\Api\MR\DailyReportController::class, 'destroy']);
    //Tour plans
    Route::get('/tour-plans', [App\Http\Controllers\Api\MR\TourPlanController::class, 'index']);
    Route::post('/tour-plans', [App\Http\Controllers\Api\MR\TourPlanController::class, 'update']);
    Route::delete('/tour-plans/{id}', [App\Http\Controllers\Api\MR\TourPlanController::class, 'destroy']);
    Route::get('/tour-plans/updated', [App\Http\Controllers\Api\MR\TourPlanController::class, 'updatedTourPlans']);
    //Daily visit
    Route::get('/visits', [App\Http\Controllers\Api\MR\VisitController::class, 'index']);
    Route::post('/visits', [App\Http\Controllers\Api\MR\VisitController::class, 'store']);
    Route::post('/visits/{id}', [App\Http\Controllers\Api\MR\VisitController::class, 'update']);
    Route::delete('/visits/{id}', [App\Http\Controllers\Api\MR\VisitController::class, 'destroy']);
});
