<?php

use Illuminate\Support\Facades\Route;

Route::prefix('manager')->name('manager.')->middleware(['web', 'auth', 'manager'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Manager\DashboardController::class, 'dashboard']);
    Route::resource('mrs', App\Http\Controllers\Manager\MRController::class);
    //task management
    Route::resource('tasks', App\Http\Controllers\Manager\TaskController::class);
    //event management
    Route::resource('events', App\Http\Controllers\Manager\EventController::class);

    //Doctor
    Route::get('/doctors/create', [App\Http\Controllers\Manager\DoctorController::class, 'add_doctor']);
    Route::post('/submit-doctor', [App\Http\Controllers\Manager\DoctorController::class, 'submit_doctor'])->name('submit.doctor');
    Route::get('/doctors', [App\Http\Controllers\Manager\DoctorController::class, 'all_doctors']);
    Route::get('/doctors/edit/{id}', [App\Http\Controllers\Manager\DoctorController::class, 'edit_doctor']);
    Route::post('/update-doctor/{id}', [App\Http\Controllers\Manager\DoctorController::class, 'update_doctor'])->name('update.doctor');
    Route::get('/delete-doctor/{id}', [App\Http\Controllers\Manager\DoctorController::class, 'delete_doctor']);

    //TADA Records
    Route::get('/tada-records', [App\Http\Controllers\Manager\TADAController::class, 'index'])->name('tada.index');
    Route::post('/ta-da/{id}/approve', [App\Http\Controllers\Manager\TADAController::class, 'approve'])->name('ta_da.approve');
    Route::post('/ta-da/{id}/reject', [App\Http\Controllers\Manager\TADAController::class, 'reject'])->name('ta_da.reject');

    //Calendar
    Route::get('/calendar', [App\Http\Controllers\Manager\CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/tasks', [App\Http\Controllers\Manager\CalendarController::class, 'getTasks'])->name('calendar.tasks');
    Route::get('/calendar/events', [App\Http\Controllers\Manager\CalendarController::class, 'getEvents'])->name('calendar.events');
    //attendance
    Route::get('/attendance', [App\Http\Controllers\Manager\AttendenceController::class, 'index'])->name('attendance.index');
    //visit plans
    Route::resource('visit-plans', App\Http\Controllers\Manager\VisitPlanController::class);
});
