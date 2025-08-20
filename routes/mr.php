<?php

use Illuminate\Support\Facades\Route;

Route::prefix('mr')->name('mr.')->middleware(['auth','mr'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\MR\DashboardController::class, 'dashboard']);
    Route::get('/attendance', [App\Http\Controllers\MR\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/check-in', [App\Http\Controllers\MR\AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('/attendance/check-out', [App\Http\Controllers\MR\AttendanceController::class, 'checkOut'])->name('attendance.checkout');
    Route::get('/attendance/monthly', [App\Http\Controllers\MR\AttendanceController::class, 'month'])->name('attendance.monthly');

});

