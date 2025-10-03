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
    Route::post('/update-mr-status/{id}', [App\Http\Controllers\Manager\MRController::class, 'update_mr_status'])->name('mr.update.status');
    //Pending uers
    Route::get('/active-users', [App\Http\Controllers\Manager\UserStatusController::class, 'all_active_users']);
    Route::get('/pending-users', [App\Http\Controllers\Manager\UserStatusController::class, 'all_pending_users']);
    Route::get('/suspend-users', [App\Http\Controllers\Manager\UserStatusController::class, 'all_suspend_users']);
    Route::post('/user/{id}/approve', [App\Http\Controllers\Manager\UserStatusController::class, 'approve_user'])->name('user.approve');
    Route::post('/users/{id}/reject', [App\Http\Controllers\Manager\UserStatusController::class, 'reject_user'])->name('user.reject');
    Route::post('/users/{id}/pending', [App\Http\Controllers\Manager\UserStatusController::class, 'pending_user'])->name('user.pending');
    //Daily visits
    Route::resource('visits', App\Http\Controllers\Manager\VisitController::class);
    Route::post('/visit-approve/{id}', [App\Http\Controllers\Manager\VisitController::class, 'approve'])->name('visit.approve');
    Route::post('/visit-reject/{id}', [App\Http\Controllers\Manager\VisitController::class, 'reject'])->name('visit.reject');
    Route::get('/visit-filter', [App\Http\Controllers\Manager\VisitController::class, 'visitFilter']);
    //Task management
    Route::resource('tasks', App\Http\Controllers\Manager\TaskController::class);
    Route::post('/task-update-status/{id}', [App\Http\Controllers\Manager\TaskController::class,'update_task_status'])->name('tasks.update.status');
    Route::get('tasks-waiting-for-approval', [App\Http\Controllers\Manager\TaskController::class,'waitingForApproval'])->name('tasks.waiting.for.approval');
    Route::post('approved-tasks/{id}', [App\Http\Controllers\Manager\TaskController::class,'approvedtasks'])->name('approved.tasks');
    Route::post('rejected-tasks/{id}', [App\Http\Controllers\Manager\TaskController::class,'rejectedtasks'])->name('rejected.tasks');
    Route::get('/tasks-calendar-for-approval', [App\Http\Controllers\Manager\TaskController::class, 'all_tasks']);
    Route::post('/tasks/approve-all', [App\Http\Controllers\Manager\TaskController::class, 'approveAll'])->name('tasks.approveAll');
    Route::post('/tasks/reject-all', [App\Http\Controllers\Manager\TaskController::class, 'rejectAll'])->name('tasks.rejectAll');
    //Event management
    Route::resource('events', App\Http\Controllers\Manager\EventController::class);
    Route::post('events/update-status/{id}', [App\Http\Controllers\Manager\EventController::class,'update_event_status'])->name('event.update.status');
    Route::get('events-waiting-for-approval', [App\Http\Controllers\Manager\EventController::class,'waitingForApproval'])->name('waiting.for.approval');
    Route::post('approved-events/{id}', [App\Http\Controllers\Manager\EventController::class,'approvedevents'])->name('approved.events');
    Route::post('rejected-events/{id}', [App\Http\Controllers\Manager\EventController::class,'rejectedevents'])->name('rejected.events');
    //Event users
    Route::get('events-active-participations', [App\Http\Controllers\Manager\EventController::class,'participations']);
    //Patient
    Route::resource('patients', App\Http\Controllers\Manager\PatientController::class);
    Route::post('/patient/{id}/approve', [App\Http\Controllers\Manager\PatientController::class, 'approve_patient'])->name('patient.approve');
    Route::post('/patient/{id}/reject', [App\Http\Controllers\Manager\PatientController::class, 'reject_patient'])->name('patient.reject');
    //Doctor
    Route::get('/doctors/create', [App\Http\Controllers\Manager\DoctorController::class, 'add_doctor']);
    Route::post('/submit-doctor', [App\Http\Controllers\Manager\DoctorController::class, 'submit_doctor'])->name('submit.doctor');
    Route::get('/doctors', [App\Http\Controllers\Manager\DoctorController::class, 'all_doctors'])->name('doctors');
    Route::get('/doctors-waiting-for-approval', [App\Http\Controllers\Manager\DoctorController::class,'waiting_for_approval']);
    Route::post('/doctor-approve/{id}', [App\Http\Controllers\Manager\DoctorController::class, 'approve'])->name('doctor.approve');
    Route::post('/doctor-reject/{id}', [App\Http\Controllers\Manager\DoctorController::class, 'reject'])->name('doctor.reject');

    Route::get('/doctors/edit/{id}', [App\Http\Controllers\Manager\DoctorController::class, 'edit_doctor']);
    Route::post('/update-doctor-status/{id}', [App\Http\Controllers\Manager\DoctorController::class, 'update_doctor_status'])->name('doctor.update.status');
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
    Route::post('/visit-plans/update-status/{id}', [App\Http\Controllers\Manager\VisitPlanController::class, 'update_visit_status'])->name('visit.update.status');
    //Daily mr reports
    Route::get('daily-mr-reports', [App\Http\Controllers\Manager\MRDailyReportController::class, 'index'])->name('daily-reports.index');
    Route::post('review-reports/{id}', [App\Http\Controllers\Manager\MRDailyReportController::class, 'review'])->name('reports.review');
    Route::get('edit-report/{id}', [App\Http\Controllers\Manager\MRDailyReportController::class, 'edit'])->name('reports.edit.daily');
    Route::PUT('update-daily-report/{id}', [App\Http\Controllers\Manager\MRDailyReportController::class, 'update'])->name('reports.update.daily');
    Route::get('export-daily-reports', [App\Http\Controllers\Manager\MRDailyReportController::class, 'export'])->name('reports.export.daily');
    //Sales
    Route::resource('sales', App\Http\Controllers\Manager\SalesController::class);
    //Camp reports
    Route::get('export-camp-report', [App\Http\Controllers\Manager\CampReportExportController::class, 'export_campReport']);
});
