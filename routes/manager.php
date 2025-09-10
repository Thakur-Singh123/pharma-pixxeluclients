<?php

use Illuminate\Support\Facades\Route;

Route::prefix('manager')->name('manager.')->middleware(['web', 'auth', 'manager'])->group(function () {
    //Profile
    Route::get('/profile', [App\Http\Controllers\Manager\ProfileController::class, 'profile']); 
    Route::get('/edit-profile', [App\Http\Controllers\Manager\ProfileController::class, 'edit_profile']);
    Route::post('/update-profile/{id}', [App\Http\Controllers\Manager\ProfileController::class, 'update_profile'])->name('update.profile');
    Route::get('/change-password', [App\Http\Controllers\Manager\ProfileController::class, 'change_password']);
    Route::post('/submit-password/{id}', [App\Http\Controllers\Manager\ProfileController::class, 'submit_change_password'])->name('submit.change.password');
    //Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Manager\DashboardController::class, 'dashboard']);
    //Mrs
    Route::resource('mrs', App\Http\Controllers\Manager\MRController::class);
    //Pending uers
    Route::get('/active-users', [App\Http\Controllers\Manager\UserStatusController::class, 'all_active_users']);
    Route::get('/pending-users', [App\Http\Controllers\Manager\UserStatusController::class, 'all_pending_users']);
    Route::get('/suspend-users', [App\Http\Controllers\Manager\UserStatusController::class, 'all_suspend_users']);
    Route::post('/user/{id}/approve', [App\Http\Controllers\Manager\UserStatusController::class, 'approve_user'])->name('user.approve');
    Route::post('/users/{id}/reject', [App\Http\Controllers\Manager\UserStatusController::class, 'reject_user'])->name('user.reject');
    Route::post('/users/{id}/pending', [App\Http\Controllers\Manager\UserStatusController::class, 'pending_user'])->name('user.pending');
    //Daily visits
    Route::resource('visits', App\Http\Controllers\Manager\VisitController::class);
    //Task management
    Route::resource('tasks', App\Http\Controllers\Manager\TaskController::class);
    Route::get('tasks-waiting-for-approval', [App\Http\Controllers\Manager\TaskController::class,'waitingForApproval'])->name('tasks.waiting.for.approval');
    Route::post('approved-tasks/{id}', [App\Http\Controllers\Manager\TaskController::class,'approvedtasks'])->name('approved.tasks');
    Route::post('rejected-tasks/{id}', [App\Http\Controllers\Manager\TaskController::class,'rejectedtasks'])->name('rejected.tasks');
    //Event management
    Route::resource('events', App\Http\Controllers\Manager\EventController::class);
    Route::get('waiting-for-approval', [App\Http\Controllers\Manager\EventController::class,'waitingForApproval'])->name('waiting.for.approval');
    Route::post('approved-events/{id}', [App\Http\Controllers\Manager\EventController::class,'approvedevents'])->name('approved.events');
    Route::post('rejected-events/{id}', [App\Http\Controllers\Manager\EventController::class,'rejectedevents'])->name('rejected.events');
    //Event users
    Route::get('active-participations', [App\Http\Controllers\Manager\EventController::class,'participations']);
    //Patient
    Route::resource('patients', App\Http\Controllers\Manager\PatientController::class);
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
    Route::get('/edit-tada/{id}', [App\Http\Controllers\Manager\TADAController::class, 'edit_tada']);
    Route::post('/update-tada/{id}', [App\Http\Controllers\Manager\TADAController::class, 'update_tada'])->name('update.tada');
    Route::get('/delete-tada/{id}', [App\Http\Controllers\Manager\TADAController::class, 'delete_tada']);
    //Calendar
    Route::get('/calendar', [App\Http\Controllers\Manager\CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/tasks', [App\Http\Controllers\Manager\CalendarController::class, 'getTasks'])->name('calendar.tasks');
    Route::get('/calendar/events', [App\Http\Controllers\Manager\CalendarController::class, 'getEvents'])->name('calendar.events');
    //Attendance
    Route::get('/attendance', [App\Http\Controllers\Manager\AttendenceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/daily', [App\Http\Controllers\Manager\AttendenceController::class, 'daily_attendance'])->name('daily.attendance');
    //visit plans
    Route::get('visit-plans', [App\Http\Controllers\Manager\VisitPlanController::class, 'index'])->name('visit-plans.index');
    Route::get('visit-plans/create', [App\Http\Controllers\Manager\VisitPlanController::class, 'create'])->name('visit-plans.create');
    Route::post('visit-plans/store', [App\Http\Controllers\Manager\VisitPlanController::class, 'store'])->name('visit-plans.store');
    //Visit plans
    Route::get('/visit-plans', [App\Http\Controllers\Manager\VisitPlanController::class, 'index'])->name('visit-plans.index');
    Route::get('/visit-plans/create', [App\Http\Controllers\Manager\VisitPlanController::class, 'create'])->name('visit-plans.create');
    Route::post('/visit-plans/store', [App\Http\Controllers\Manager\VisitPlanController::class, 'store'])->name('visit-plans.store');
    Route::get('/edit-visit-plan/{id}', [App\Http\Controllers\Manager\VisitPlanController::class, 'edit']);
    Route::post('/visit-plan/update/{id}', [App\Http\Controllers\Manager\VisitPlanController::class, 'update'])->name('visit-plans.update');
    Route::get('/delete-visit-plan/{id}', [App\Http\Controllers\Manager\VisitPlanController::class, 'delete']);
    Route::get('/visit-plans/interested-mrs', [App\Http\Controllers\Manager\VisitPlanController::class, 'showInterestedMRS'])->name('visit.plans.interested.mrs');
    Route::post('/visit-plans/action/{id}', [App\Http\Controllers\Manager\VisitPlanController::class, 'approveRejectInterest'])->name('visit-plans.action');
    Route::post('/visit-plans/add-comment/', [App\Http\Controllers\Manager\VisitPlanController::class, 'add_comment'])->name('visit-plans.add-comment');
    //Daily mr reports
    Route::get('daily-mr-reports', [App\Http\Controllers\Manager\MRDailyReportController::class, 'index'])->name('daily-reports.index');
    Route::post('review-reports/{id}', [App\Http\Controllers\Manager\MRDailyReportController::class, 'review'])->name('reports.review');
    Route::get('edit-report/{id}', [App\Http\Controllers\Manager\MRDailyReportController::class, 'edit'])->name('reports.edit.daily');
    Route::PUT('update-daily-report/{id}', [App\Http\Controllers\Manager\MRDailyReportController::class, 'update'])->name('reports.update.daily');
    Route::get('export-daily-reports', [App\Http\Controllers\Manager\MRDailyReportController::class, 'export'])->name('reports.export.daily');
});
