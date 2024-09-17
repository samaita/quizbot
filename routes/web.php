<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// Telegram webhook route
Route::post('/telegram/webhook', function (Request $request) {
    Log::info('Telegram webhook hit', $request->all());
    return response()->json(['message' => 'Webhook received']);
});
