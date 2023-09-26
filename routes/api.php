<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VehicleTypeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/vehicle-types', [VehicleTypeController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::apiResource('products', ProductController::class)->only(['index']);


Route::post('/products/{id}/update-image', [ProductController::class, 'updateImage']);
Route::put('products/{product}', [ProductController::class, 'update'])->middleware('auth:sanctum');


Route::post('/products', [ProductController::class, 'store'])->middleware('auth:sanctum');

Route::get('/products/search', [ProductController::class, 'search']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->middleware('auth:sanctum');


Route::get('/brands', [BrandController::class, 'index']);
Route::post('/brands', [BrandController::class, 'store'])->middleware('auth:sanctum');
Route::delete('/brands/{id}', [BrandController::class, 'destroy'])->middleware('auth:sanctum');


Route::post('/vehicle-types', [VehicleTypeController::class, 'store'])->middleware('auth:sanctum');
Route::post('/categories', [CategoryController::class, 'store'])->middleware('auth:sanctum');

Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->middleware('auth:sanctum');
Route::delete('/vehicle-types/{vehicleType}', [VehicleTypeController::class, 'destroy'])->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/register', [AuthController::class, 'register'])->middleware('auth:sanctum');

Route::get('/product-main' , [ProductController::class,'productSmall']);
