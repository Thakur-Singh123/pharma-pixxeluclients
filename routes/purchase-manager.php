<?php

use Illuminate\Support\Facades\Route;

//PurchaseManager Route
Route::prefix('purchase-manager')->name('purchase-manager.')->middleware(['web','auth','purchase-manager'])->group(function () {
  //Profile
  Route::get('/profile', [App\Http\Controllers\PurchaseManager\ProfileController::class, 'profile']); 
  Route::get('/edit-profile', [App\Http\Controllers\PurchaseManager\ProfileController::class, 'edit_profile']);
  Route::post('/update-profile/{id}', [App\Http\Controllers\PurchaseManager\ProfileController::class, 'update_profile'])->name('update.profile');
  Route::get('/change-password', [App\Http\Controllers\PurchaseManager\ProfileController::class, 'change_password']);
  Route::post('/submit-password/{id}', [App\Http\Controllers\PurchaseManager\ProfileController::class, 'submit_change_password'])->name('submit.change.password');
  //Dashboard
  Route::get('/dashboard', [App\Http\Controllers\PurchaseManager\DashboardController::class, 'dashboard']);

  Route::get('purchase-orders/create', [\App\Http\Controllers\PurchaseManager\PurchaseOrderController::class, 'create'])->name('purchase-orders.create');
  Route::post('purchase-orders', [\App\Http\Controllers\PurchaseManager\PurchaseOrderController::class, 'store'])->name('purchase-orders.store');
  // (optional) index/show routes
  Route::get('purchase-orders', [\App\Http\Controllers\PurchaseManager\PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
  Route::get('purchase-orders/{id}/edit', [\App\Http\Controllers\PurchaseManager\PurchaseOrderController::class, 'edit'])->name('purchase-orders.edit');
  Route::put('purchase-orders/{id}', [\App\Http\Controllers\PurchaseManager\PurchaseOrderController::class, 'update'])->name('purchase-orders.update');
  Route::delete('purchase-orders/{id}', [\App\Http\Controllers\PurchaseManager\PurchaseOrderController::class, 'destroy'])->name('purchase-orders.destroy');

  Route::get('purchase-orders/export', [\App\Http\Controllers\PurchaseManager\PurchaseOrderController::class, 'export'])
  ->name('purchase-orders.export'); 
});

