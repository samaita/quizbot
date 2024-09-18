<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\TelegramWebhookController;

// Telegram webhook route
Route::post('/telegram/webhook', [TelegramWebhookController::class, 'handle']);
