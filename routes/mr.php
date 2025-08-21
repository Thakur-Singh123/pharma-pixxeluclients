<?php

use Illuminate\Support\Facades\Route;

Route::prefix('mr')->name('mr.')->middleware(['web','auth','mr'])->group(function () {
    //Profile
    Route::get('/profile', [App\Http\Controllers\MR\ProfileController::class, 'profile']); 
    Route::get('/edit-profile', [App\Http\Controllers\MR\ProfileController::class, 'edit_profile']);
    Route::post('/update-profile/{id}', [App\Http\Controllers\MR\ProfileController::class, 'update_profile'])->name('update.profile');

    Route::get('/dashboard', [App\Http\Controllers\MR\DashboardController::class, 'dashboard']);
    Route::get('/attendance', [App\Http\Controllers\MR\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/check-in', [App\Http\Controllers\MR\AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('/attendance/check-out', [App\Http\Controllers\MR\AttendanceController::class, 'checkOut'])->name('attendance.checkout');
    Route::get('/attendance/monthly', [App\Http\Controllers\MR\AttendanceController::class, 'month'])->name('attendance.monthly');
    //Daily visit
    Route::get('/visit-create', [App\Http\Controllers\MR\VisitController::class, 'add_visit']);
    Route::post('/submit-visit', [App\Http\Controllers\MR\VisitController::class, 'submit_visit'])->name('submit.visit');
    Route::get('/visits', [App\Http\Controllers\MR\VisitController::class, 'all_visits']);
    Route::get('/edit-visit/{id}', [App\Http\Controllers\MR\VisitController::class, 'edit_visit']);
    Route::post('/update-visit/{id}', [App\Http\Controllers\MR\VisitController::class, 'update_visit'])->name('update.visit');
    Route::get('/delete-visit/{id}', [App\Http\Controllers\MR\VisitController::class, 'delete_visit']);
    //Task
    Route::get('/tasks', [App\Http\Controllers\MR\TaskController::class, 'all_tasks']);
    //Doctor
    Route::get('/doctor-create', [App\Http\Controllers\MR\DoctorController::class, 'add_doctor']);
    Route::post('/submit-doctor', [App\Http\Controllers\MR\DoctorController::class, 'submit_doctor'])->name('submit.doctor');
    Route::get('/doctors', [App\Http\Controllers\MR\DoctorController::class, 'all_doctors']);
    Route::get('/edit-doctor/{id}', [App\Http\Controllers\MR\DoctorController::class, 'edit_doctor']);
    Route::post('/update-doctor/{id}', [App\Http\Controllers\MR\DoctorController::class, 'update_doctor'])->name('update.doctor');
    Route::get('/delete-doctor/{id}', [App\Http\Controllers\MR\DoctorController::class, 'delete_doctor']);
});

