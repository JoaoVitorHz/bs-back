<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use  App\Http\Controllers\UserController;
use  App\Http\Controllers\AuthController;

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::get('/auth/teste123', [AuthController::class, 'teste123']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
