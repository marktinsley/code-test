<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', fn(Request $request) => $request->user());

    Route::apiResource('products', \App\Http\Controllers\ProductsController::class);
    Route::apiResource('user-products', \App\Http\Controllers\UserProductsController::class)
        ->only('index', 'update', 'destroy');
});
