<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::inertia('/login', 'Welcome')->name('login');
    Route::inertia('/', 'Welcome');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [AuthController::class, 'dashboard']);
    Route::delete('/logout', [AuthController::class, 'logout']);

    // properties group
    Route::prefix('properties')
        ->controller(PropertyController::class)
        ->group(function () {
            Route::get('/', 'index');

            Route::get('/{id}/edit', 'edit');
            Route::put('/{id}', 'update');
            Route::post('/', 'store');
            Route::get('/create', 'create');
            Route::get('/transfer', 'transferForm');
            Route::post('/transfer', 'transfer');
            Route::get('/inventory-report', 'inventoryReport');

            // Route::get('/{id}/edit', 'edit');
            // Route::put('/{id}/update', 'update');

            // Route::delete('/batches/{id}', 'deleteBatch');

            // Route::get('/{id}/manage-allocation', 'manageAllocation');
            // Route::get('/{id}/generate-allocation-list', 'generateAllocationList');

            // Route::post('/{id}/allocation', 'storeAllocation');
            // Route::delete('/allocation/{id}', 'deleteAllocation');
            // Route::patch('/allocation/{id}/delivered', 'setAllocationDelivered');
        });

    // inventory group
    // Route::prefix('status-levels')
    //     ->controller(StatusLevelController::class)
    //     ->group(function () {
    //         Route::get('/near-expiry', 'nearExpiry');

    //     });

});
