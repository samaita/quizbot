<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Webhook hit', $request->all());

        try {
            $update = Telegram::commandsHandler(true);

            $message = $update->getMessage();
            $chat_id = $message->getChat()->getId();
            $text = $message->getText();

            Log::info('Processed message', ['chat_id' => $chat_id, 'text' => $text]);

            Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => "You said: $text",
            ]);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Error in webhook', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}