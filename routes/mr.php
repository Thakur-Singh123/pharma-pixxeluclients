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
    Route::get('/dashboard', [App\Http\Controllers\MR\DashboardController::class, 'dashboard'])->name('dashboard');
    //Attendances
    Route::get('/attendance', [App\Http\Controllers\MR\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/check-in', [App\Http\Controllers\MR\AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('/attendance/check-out', [App\Http\Controllers\MR\AttendanceController::class, 'checkOut'])->name('attendance.checkout');
    Route::get('/attendance/monthly', [App\Http\Controllers\MR\AttendanceController::class, 'month'])->name('attendance.monthly');
    //Clients
    Route::resource('/clients', App\Http\Controllers\MR\ClientController::class);
    //Daily visits
    Route::get('/visits/create', [App\Http\Controllers\MR\VisitController::class, 'add_visit']);
    Route::post('/submit-visit', [App\Http\Controllers\MR\VisitController::class, 'submit_visit'])->name('submit.visit');
    Route::get('/visits', [App\Http\Controllers\MR\VisitController::class, 'all_visits'])->name('visits');
    Route::get('/visits-export', [App\Http\Controllers\MR\VisitController::class, 'export'])->name('visits.export');
    Route::get('/areas-served', [App\Http\Controllers\MR\VisitController::class, 'areas_served']);
    Route::get('/visit-filter', [App\Http\Controllers\MR\VisitController::class, 'visitFilter']);
    Route::get('/visits/edit/{id}', [App\Http\Controllers\MR\VisitController::class, 'edit_visit']);
    Route::post('/update-visit/{id}', [App\Http\Controllers\MR\VisitController::class, 'update_visit'])->name('update.visit');
    Route::get('/delete-visit/{id}', [App\Http\Controllers\MR\VisitController::class, 'delete_visit']);
    //Task
    Route::resource('/tasks', App\Http\Controllers\MR\TaskController::class);
    Route::get('/tasks-assigned-by-manager', [App\Http\Controllers\MR\TaskController::class,'assign_manger']);
    Route::get('/tasks-himself', [App\Http\Controllers\MR\TaskController::class,'himself']);
    Route::get('/pending-approval', [App\Http\Controllers\MR\TaskController::class,'pending_approval']);
    Route::post('/task-update-status/{id}', [App\Http\Controllers\MR\TaskController::class,'update_status'])->name('tasks.update.status');
    //Tour plans
    Route::resource('/assigned-tour-plans', App\Http\Controllers\MR\TourPlanController::class);
    Route::get('/updated-tour-plans', [App\Http\Controllers\MR\TourPlanController::class, 'updated_tour_plans'])->name('update.plan');
    Route::get('/delete-tour-plan/{id}', [App\Http\Controllers\MR\TourPlanController::class, 'delete_tour_plan']);
    //Task Calender
    Route::post('/tasks/send-monthly', [App\Http\Controllers\MR\TaskController::class,'sendMonthlyTasksToManager'])->name('tasks.sendMonthly');
    Route::get('/tasks-calendar-approved-by-manager', [App\Http\Controllers\MR\TaskController::class,'approved_tasks']);
    Route::get('/tasks-calendar-rejected-by-manager', [App\Http\Controllers\MR\TaskController::class,'rajected_tasks']);
    Route::post('/task-update-calender-status', [App\Http\Controllers\MR\TaskController::class,'update_calender_status'])->name('task.calender.update.status');
    //Problems & Challenge
    Route::resource('/problems', App\Http\Controllers\MR\ProblemController::class);
    //Doctors
    Route::get('/doctors', [App\Http\Controllers\MR\DoctorController::class, 'index'])->name('doctors.index');
    Route::post('/doctors/submit', [App\Http\Controllers\MR\DoctorController::class, 'submit_doctor'])->name('doctors.submit');
    //Patients
    Route::resource('/patients', App\Http\Controllers\MR\ReferredPatientController::class);
    //TADA
    Route::resource('/tada', App\Http\Controllers\MR\TADAController::class);
    //Calnedar
    Route::get('/calendar', [App\Http\Controllers\MR\CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/tasks', [App\Http\Controllers\MR\CalendarController::class, 'getTasks'])->name('calendar.tasks');
    Route::get('/calendar/events', [App\Http\Controllers\MR\CalendarController::class, 'getEvents'])->name('calendar.events');
    //Events
    Route::get('events', [App\Http\Controllers\MR\EventController::class,'index'])->name('events.index');
    Route::get('events/create', [App\Http\Controllers\MR\EventController::class,'create'])->name('events.create');
    Route::post('events/store', [App\Http\Controllers\MR\EventController::class,'store'])->name('events.store');
    Route::get('events/edit/{id}', [App\Http\Controllers\MR\EventController::class,'edit'])->name('events.edit');
    Route::post('events/update-status/{id}', [App\Http\Controllers\MR\EventController::class,'update_event_status'])->name('event.update.status');
    Route::put('events/update/{id}', [App\Http\Controllers\MR\EventController::class,'update'])->name('events.update');
    Route::DELETE('events/delete/{id}', [App\Http\Controllers\MR\EventController::class,'destroy'])->name('events.destroy');
    Route::get('events/pending-for-approval', [App\Http\Controllers\MR\EventController::class,'pendingForApproval'])->name('events.pending-for-approval');
    Route::get('events-assigned-by-manager', [App\Http\Controllers\MR\EventController::class,'assign_manger'])->name('events.assign-manger');
    Route::get('events-himself', [App\Http\Controllers\MR\EventController::class,'himself'])->name('events.himself');
    //Event users
    Route::get('events-active-participations', [App\Http\Controllers\MR\EventController::class,'participations']);
    //Visit plans
    Route::get('visit-plans', [App\Http\Controllers\MR\VisitPlanController::class,'index'])->name('visit-plans.index');
    Route::post('visit-plans/{id}/interested', [App\Http\Controllers\MR\VisitPlanController::class,'expressInterest'])->name('visit-plan.interested');
    Route::get('visit-plans/my-interested-plans', [App\Http\Controllers\MR\VisitPlanController::class,'myInterestedPlans'])->name('visit-plans.my-interested');
    Route::get('visit-plans/my-assigned-plans', [App\Http\Controllers\MR\VisitPlanController::class,'myAssignedPlans'])->name('visit-plans.my-assigned');
    //Daily reports
    Route::resource('daily-reports', App\Http\Controllers\MR\MRDailyReportController::class);
    //Camp reports
    Route::get('export-camp-report', [App\Http\Controllers\MR\CampReportExportController::class, 'export_campReport']);
});

//sales
Route::prefix('mr')->name('mr.')->middleware(['web','auth','can_sales'])->group(function () {
    Route::resource('/sales', App\Http\Controllers\MR\SalesController::class);
});

