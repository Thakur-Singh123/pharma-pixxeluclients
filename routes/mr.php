<?php

use Illuminate\Support\Facades\Route;

Route::prefix('mr')->name('mr.')->middleware(['web','auth','mr'])->group(function () {
    //Profile
    Route::get('/profile', [App\Http\Controllers\MR\ProfileController::class, 'profile']); 
    Route::get('/edit-profile', [App\Http\Controllers\MR\ProfileController::class, 'edit_profile']);
    Route::post('/update-profile/{id}', [App\Http\Controllers\MR\ProfileController::class, 'update_profile'])->name('update.profile');
    Route::get('/change-password', [App\Http\Controllers\MR\ProfileController::class, 'change_password']);
    Route::post('/submit-password/{id}', [App\Http\Controllers\MR\ProfileController::class, 'submit_change_password'])->name('submit.change.password');
    //Dashboard
    Route::get('/dashboard', [App\Http\Controllers\MR\DashboardController::class, 'dashboard']);
    //Attendances
    Route::get('/attendance', [App\Http\Controllers\MR\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/check-in', [App\Http\Controllers\MR\AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('/attendance/check-out', [App\Http\Controllers\MR\AttendanceController::class, 'checkOut'])->name('attendance.checkout');
    Route::get('/attendance/monthly', [App\Http\Controllers\MR\AttendanceController::class, 'month'])->name('attendance.monthly');
    //Daily visits
    Route::get('/visits/create', [App\Http\Controllers\MR\VisitController::class, 'add_visit']);
    Route::post('/submit-visit', [App\Http\Controllers\MR\VisitController::class, 'submit_visit'])->name('submit.visit');
    Route::get('/visits', [App\Http\Controllers\MR\VisitController::class, 'all_visits']);
    Route::get('/visits/edit/{id}', [App\Http\Controllers\MR\VisitController::class, 'edit_visit']);
    Route::post('/update-visit/{id}', [App\Http\Controllers\MR\VisitController::class, 'update_visit'])->name('update.visit');
    Route::get('/delete-visit/{id}', [App\Http\Controllers\MR\VisitController::class, 'delete_visit']);
    //Tasks
    Route::get('/tasks', [App\Http\Controllers\MR\TaskController::class, 'all_tasks']);
    //Doctors
    Route::get('/doctors', [App\Http\Controllers\MR\DoctorController::class, 'index'])->name('doctors.index');
    Route::post('/doctors/submit', [App\Http\Controllers\MR\DoctorController::class, 'submit_doctor'])->name('doctors.submit');
    //Patients
    Route::resource('/patients', App\Http\Controllers\MR\PatientController::class);
    //TADA
    Route::resource('/tada', App\Http\Controllers\MR\TADAController::class);
    //Calnedar
    Route::get('/calendar', [App\Http\Controllers\MR\CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/tasks', [App\Http\Controllers\MR\CalendarController::class, 'getTasks'])->name('calendar.tasks');
    Route::get('/calendar/events', [App\Http\Controllers\MR\CalendarController::class, 'getEvents'])->name('calendar.events');
    //Events
    Route::get('events', [App\Http\Controllers\MR\EventController::class,'index']);
    //Visit plans
    Route::get('visit-plans', [App\Http\Controllers\MR\VisitPlanController::class,'index'])->name('visit-plans.index');
    Route::post('visit-plans/{id}/interested', [App\Http\Controllers\MR\VisitPlanController::class,'expressInterest'])->name('visit-plan.interested');
    Route::get('visit-plans/my-interested-plans', [App\Http\Controllers\MR\VisitPlanController::class,'myInterestedPlans'])->name('visit-plans.my-interested');
    Route::get('visit-plans/my-assigned-plans', [App\Http\Controllers\MR\VisitPlanController::class,'myAssignedPlans'])->name('visit-plans.my-assigned');
    //Daily reports
    Route::resource('daily-reports', App\Http\Controllers\MR\MRDailyReportController::class);
});

