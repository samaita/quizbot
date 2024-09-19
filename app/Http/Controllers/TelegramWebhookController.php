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

            switch ($text) {
                case '/start':
                    if ($user) {
                        $response = ResponseHelper::getResponse('welcome_back', ['name' => $user->name]);
                    } else {
                        $response = ResponseHelper::getResponse('welcome_not_registered');
                    }
                    break;

                case '/register':
                    if ($user) {
                        $response = ResponseHelper::getResponse('already_registered', ['name' => $user->name]);
                    } else {
                        // Create a new user
                        $newUser = new UserTelegram();
                        $newUser->telegram_chat_id = $chat_id;
                        $newUser->name = $message->getFrom()->getFirstName();
                        $newUser->save();

                        ResponseHelper::getResponse('success_register', ['name' => $newUser->name]);
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
                        ? ResponseHelper::getResponse('echo', ['name' => $user->name, 'text' => $text])
                        : ResponseHelper::getResponse('please_register');
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