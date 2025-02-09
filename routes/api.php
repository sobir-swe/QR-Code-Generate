<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/qr/generate', [ApiController::class, 'generate']);
Route::post('/qr/read', [ApiController::class, 'read']);
