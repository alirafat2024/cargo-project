<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\categoryController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ShipmentController;
use App\Http\Controllers\Api\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    
    Route::apiResource('branches', BranchController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('shipments', ShipmentController::class);
    Route::get('/category',categoryController::class,'index');
});
