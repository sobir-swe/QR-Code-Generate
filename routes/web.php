<?php

use App\Http\Controllers\QRController;
use Illuminate\Support\Facades\Route;

// routes/web.php
Route::get('/qr', [QRController::class, 'index']);
Route::post('/qr/generate', [QRController::class, 'generate']);
Route::post('/qr/read', [QRController::class, 'read']);


//// Telegram webhook
//Route::post('/telegram/webhook', [TelegramController::class, 'handle']);
