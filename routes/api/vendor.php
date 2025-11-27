<?php

use Illuminate\Support\Facades\Route;


//Vendor api
Route::prefix('vendor')->middleware(['ensure.token','auth:sanctum', 'vendor'])->group(function () {
    //Dashboard 
    Route::get('/dashboard', [App\Http\Controllers\Api\Vendor\DashboardController::class, 'dashboard']);
    //Vendor
    Route::get('/purchase-orders', [App\Http\Controllers\Api\Vendor\PurchaseOrderController::class, 'index']);
    Route::post('/purchase-order/{id}', [App\Http\Controllers\Api\Vendor\PurchaseOrderController::class, 'update']);       
});
