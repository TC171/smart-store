<?php

use App\Http\Controllers\Frontend\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\ProductController; // ✅ thêm dòng này

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
// Route so sánh sản phẩm bằng AI
Route::get('/compare-product/{id}', [ProductController::class, 'compareProduct']);
Route::get('/products', [HomeController::class, 'loadProducts']);
// <?php

// use App\Http\Controllers\Frontend\HomeController;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Frontend\ProductController; // ✅ thêm dòng này

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
// // Route so sánh sản phẩm bằng AI
// Route::get('/compare-product/{id}', [ProductController::class, 'compareProduct']);
// Route::get('/products', [HomeController::class, 'loadProducts']);
