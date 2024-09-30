<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomerController;
use App\Http\Middleware\EnsureTokenIsValid;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'loginForm'])->name('login');

Route::get('/register', [AuthController::class, 'showRegistration'])->name('register');
Route::post('/register', [AuthController::class, 'registration'])->name('register');

//  dashboard
Route::middleware(EnsureTokenIsValid::class)->get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

// customer management
Route::middleware(EnsureTokenIsValid::class)->get('/customerManagement', [CustomerController::class, 'customerManagement'])->name('customer.management');

// sales management
Route::middleware(EnsureTokenIsValid::class)->get('/salesManagement', [CustomerController::class, 'salesManagement'])->name('sales.management');
