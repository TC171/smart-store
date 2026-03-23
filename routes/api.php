<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;

// API mặc định
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API Categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{slug}', [CategoryController::class, 'show']);