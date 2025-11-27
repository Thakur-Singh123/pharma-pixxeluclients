<?php

use Illuminate\Support\Facades\Route;

//Purchase Manager api
Route::prefix('purchase-manager')->middleware(['ensure.token','auth:sanctum', 'purchase-manager'])->group(function () {
    //Dashboard 
    Route::get('/dashboard', [App\Http\Controllers\Api\PurchaseManager\PurchaseOrderController::class, 'dashboard']);
    //Purchase order 
    Route::get('/vendors', [App\Http\Controllers\Api\PurchaseManager\PurchaseOrderController::class, 'vendor']); 
    Route::get('/purchase-orders', [App\Http\Controllers\Api\PurchaseManager\PurchaseOrderController::class, 'index']);
    Route::post('/purchase-order', [App\Http\Controllers\Api\PurchaseManager\PurchaseOrderController::class, 'store']);
    Route::post('/purchase-order/{id}', [App\Http\Controllers\Api\PurchaseManager\PurchaseOrderController::class, 'update']);  
    Route::delete('/purchase-order/{id}', [App\Http\Controllers\Api\PurchaseManager\PurchaseOrderController::class, 'destroy']);     
});
