<?php

use App\Http\Controllers\Api\ProductController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/user', function(Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Register
Route::post('/register', [AuthController::class, 'register']);

// Login
Route::post('/login', [AuthController::class, 'login']);
// Logout
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Products
Route::apiResource('/api-products', ProductController::class)->middleware('auth:sanctum');