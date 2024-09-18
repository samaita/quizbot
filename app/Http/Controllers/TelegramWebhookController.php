<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Log;
use App\Models\UserTelegram;

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

            $user = UserTelegram::where('telegram_chat_id', $chat_id)->first();

            switch ($text) {
                case '/start':
                    if ($user) {
                        $response = "Welcome back, {$user->name}! How can I assist you today?";
                    } else {
                        $response = "Welcome! You're not registered yet. Please use /register to sign up.";
                    }
                    break;

                case '/register':
                    if ($user) {
                        $response = "You're already registered, {$user->name}!";
                    } else {
                        // Create a new user
                        $newUser = new UserTelegram();
                        $newUser->telegram_chat_id = $chat_id;
                        $newUser->name = $message->getFrom()->getFirstName();
                        $newUser->save();

                        $response = "Thank you for registering, {$newUser->name}! You're now all set.";
                    }
                    break;

                case '/status':
                    if ($user) {
                        $response = "You are registered as {$user->name}.";
                    } else {
                        $response = "You have not registered yet. Use /register to sign up.";
                    }
                    break;

                default:
                    $response = $user 
                        ? "Hello {$user->name}, you said: $text" 
                        : "You said: $text. Please register using /register to get personalized responses.";
            }

            Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => $response,
            ]);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Error in webhook', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}