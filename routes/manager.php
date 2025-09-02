<?php

use Illuminate\Support\Facades\Route;

Route::prefix('manager')->name('manager.')->middleware(['web', 'auth', 'manager'])->group(function () {
    //Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Manager\DashboardController::class, 'dashboard']);
    Route::resource('mrs', App\Http\Controllers\Manager\MRController::class);
    //Task management
    Route::resource('tasks', App\Http\Controllers\Manager\TaskController::class);
    //Event management
    Route::resource('events', App\Http\Controllers\Manager\EventController::class);
    //Doctor
    Route::get('/doctors/create', [App\Http\Controllers\Manager\DoctorController::class, 'add_doctor']);
    Route::post('/submit-doctor', [App\Http\Controllers\Manager\DoctorController::class, 'submit_doctor'])->name('submit.doctor');
    Route::get('/doctors', [App\Http\Controllers\Manager\DoctorController::class, 'all_doctors'])->name('doctors');
    Route::get('/doctors/edit/{id}', [App\Http\Controllers\Manager\DoctorController::class, 'edit_doctor']);
    Route::post('/update-doctor/{id}', [App\Http\Controllers\Manager\DoctorController::class, 'update_doctor'])->name('update.doctor');
    Route::get('/delete-doctor/{id}', [App\Http\Controllers\Manager\DoctorController::class, 'delete_doctor']);
    //TADA records
    Route::get('/tada-records', [App\Http\Controllers\Manager\TADAController::class, 'index'])->name('tada.index');
    Route::post('/ta-da/{id}/approve', [App\Http\Controllers\Manager\TADAController::class, 'approve'])->name('ta_da.approve');
    Route::post('/ta-da/{id}/reject', [App\Http\Controllers\Manager\TADAController::class, 'reject'])->name('ta_da.reject');
    //Calendar
    Route::get('/calendar', [App\Http\Controllers\Manager\CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/tasks', [App\Http\Controllers\Manager\CalendarController::class, 'getTasks'])->name('calendar.tasks');
    Route::get('/calendar/events', [App\Http\Controllers\Manager\CalendarController::class, 'getEvents'])->name('calendar.events');
    //Attendance
    Route::get('/attendance', [App\Http\Controllers\Manager\AttendenceController::class, 'index'])->name('attendance.index');
    //Visit plans
    Route::get('/visit-plans', [App\Http\Controllers\Manager\VisitPlanController::class, 'index'])->name('visit-plans.index');
    Route::get('/visit-plans/create', [App\Http\Controllers\Manager\VisitPlanController::class, 'create'])->name('visit-plans.create');
    Route::post('/visit-plans/store', [App\Http\Controllers\Manager\VisitPlanController::class, 'store'])->name('visit-plans.store');
    Route::get('/edit-visit-plan/{id}', [App\Http\Controllers\Manager\VisitPlanController::class, 'edit']);
    Route::get('/visit-plans/interested-mrs', [App\Http\Controllers\Manager\VisitPlanController::class, 'showInterestedMRS'])->name('visit.plans.interested.mrs');
    Route::post('/visit-plans/action/{id}', [App\Http\Controllers\Manager\VisitPlanController::class, 'approveRejectInterest'])->name('visit-plans.action');
    Route::post('/visit-plans/add-comment/', [App\Http\Controllers\Manager\VisitPlanController::class, 'add_comment'])->name('visit-plans.add-comment');
    //Daily mr reports
    Route::get('daily-mr-reports', [App\Http\Controllers\Manager\MRDailyReportController::class, 'index']);
    Route::post('review-reports/{id}', [App\Http\Controllers\Manager\MRDailyReportController::class, 'review'])->name('reports.review');
});
