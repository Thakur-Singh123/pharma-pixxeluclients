<?php

use Illuminate\Support\Facades\Route;

Route::prefix('manager')->name('manager.')->middleware(['web', 'auth', 'manager'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Manager\DashboardController::class, 'dashboard']);
    Route::resource('mrs', App\Http\Controllers\Manager\MRController::class);
    Route::resource('tasks', App\Http\Controllers\Manager\TaskController::class);

    //Doctor
    Route::get('/doctors/create', [App\Http\Controllers\Manager\DoctorController::class, 'add_doctor']);
    Route::post('/submit-doctor', [App\Http\Controllers\Manager\DoctorController::class, 'submit_doctor'])->name('submit.doctor');
    Route::get('/doctors', [App\Http\Controllers\Manager\DoctorController::class, 'all_doctors']);
    Route::get('/doctors/edit/{id}', [App\Http\Controllers\Manager\DoctorController::class, 'edit_doctor']);
    Route::post('/update-doctor/{id}', [App\Http\Controllers\Manager\DoctorController::class, 'update_doctor'])->name('update.doctor');
    Route::get('/delete-doctor/{id}', [App\Http\Controllers\Manager\DoctorController::class, 'delete_doctor']);
});
