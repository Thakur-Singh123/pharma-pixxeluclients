<?php

use Illuminate\Support\Facades\Route;

//Vendor Route
Route::prefix('vendor')->name('vendor.')->middleware(['web','auth','vendor'])->group(function () {
  //Profile
  Route::get('/profile', [App\Http\Controllers\Vendor\ProfileController::class, 'profile']); 
  Route::get('/edit-profile', [App\Http\Controllers\Vendor\ProfileController::class, 'edit_profile']);
  Route::post('/update-profile/{id}', [App\Http\Controllers\Vendor\ProfileController::class, 'update_profile'])->name('update.profile');
  Route::get('/change-password', [App\Http\Controllers\Vendor\ProfileController::class, 'change_password']);
  Route::post('/submit-password/{id}', [App\Http\Controllers\Vendor\ProfileController::class, 'submit_change_password'])->name('submit.change.password');
  //Dashboard
  Route::get('/dashboard', [App\Http\Controllers\Vendor\DashboardController::class, 'dashboard'])->name('dashboard');
  //Purchase Order
   Route::get('purchase-orders', [App\Http\Controllers\Vendor\PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
   Route::post('purchase-orders/{id}/update-delivery', [App\Http\Controllers\Vendor\PurchaseOrderController::class, 'updateDelivery'])
    ->name('purchase-orders.update.delivery');
  Route::get('purchase-orders/export', [App\Http\Controllers\Vendor\PurchaseOrderController::class, 'export'])
    ->name('purchase-orders.export');

});

