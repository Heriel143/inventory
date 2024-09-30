<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function () {
    return response()->json(
        [
            'message' => 'Hello World!',
        ]
    );
})->middleware('auth:api');

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::middleware('auth:api')->get('user', [AuthController::class, 'me']);

// logout
Route::middleware('auth:api')->post('logout', [AuthController::class, 'logout']);

// customer
Route::apiResource('customers', CustomerController::class)->middleware('auth:api');

// sales
Route::apiResource('sales', SaleController::class)->middleware('auth:api');

Route::middleware('auth:api')->get('/sales/total/{id}', [SaleController::class, 'getTotal']);
