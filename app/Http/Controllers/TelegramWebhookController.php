<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Log;
use App\Models\UserTelegram;
use App\Helpers\ResponseHelper;

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

            $response = match ($text) {
                '/start' => $user
                    ? ResponseHelper::getResponse('welcome_back', ['name' => $user->name])
                    : ResponseHelper::getResponse('welcome_not_registered'),

                '/register' => $this->handleRegistration($user, $chat_id, $message),

                '/status' => $user
                    ? ResponseHelper::getResponse('status_registered', ['name' => $user->name])
                    : ResponseHelper::getResponse('status_not_registered'),

                default => $user
                    ? ResponseHelper::getResponse('echo', ['name' => $user->name, 'text' => $text])
                    : ResponseHelper::getResponse('please_register'),
            };

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

    private function handleRegistration($user, $chat_id, $message)
    {
        if ($user) {
            return ResponseHelper::getResponse('already_registered', ['name' => $user->name]);
        }

        $newUser = UserTelegram::create([
            'telegram_chat_id' => $chat_id,
            'name' => $message->getFrom()->getFirstName(),
        ]);

        return ResponseHelper::getResponse('success_register', ['name' => $newUser->name]);
    }
}