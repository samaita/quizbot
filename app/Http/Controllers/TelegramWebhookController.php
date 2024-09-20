<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Log;
use App\Models\UserTelegram;
use App\Models\Question;
use App\Models\QuizLog;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\DB;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Webhook hit', $request->all());
        try {
            $update = Telegram::commandsHandler(true);
            
            if ($update->isType('callback_query')) {
                return $this->handleCallbackQuery($update->getCallbackQuery());
            }

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
                '/soal' => $this->handleQuizQuestion($chat_id),
                default => ResponseHelper::getResponse('echo', ['name' => $user->name ?? 'Guest', 'text' => $text]),
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

    private function handleCallbackQuery($callbackQuery)
    {
        $data = $callbackQuery->getData();
        $chat_id = $callbackQuery->getMessage()->getChat()->getId();
        
        if (strpos($data, 'answer_') === 0) {
            $userAnswer = substr($data, 7, 1); // Extract the answer letter
            $response = $this->evaluateAnswer($chat_id, $userAnswer);
            
            Telegram::answerCallbackQuery([
                'callback_query_id' => $callbackQuery->getId(),
                'text' => $response,
            ]);
            
            // Send a new message with the result
            Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => $response,
            ]);
        }

        return response()->json(['status' => 'success']);
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

    private function handleQuizQuestion($chat_id)
    {
        $user = UserTelegram::where('telegram_chat_id', $chat_id)->first();

        if (!$user) {
            return "Please register first using /register command.";
        }

        $lastUnansweredLog = QuizLog::where('user_telegram_id', $user->id)
            ->where('is_answered', false)
            ->orderBy('created_at', 'asc')
            ->first();

        if ($lastUnansweredLog) {
            $question = Question::find($lastUnansweredLog->question_id);
        } else {
            $question = Question::inRandomOrder()->first();
            if (!$question) {
                return "Sorry, no questions available at the moment.";
            }
            QuizLog::create([
                'user_telegram_id' => $user->id,
                'question_id' => $question->id,
                'correct_answer' => $question->answer,
            ]);
        }

        $additional_data = $question->additional_data;
        $choices = $additional_data['choices'];

        $keyboard = [];
        foreach ($choices as $key => $value) {
            $keyboard[] = [['text' => "$key. $value", 'callback_data' => "answer_$key"]];
        }

        Telegram::sendMessage([
            'chat_id' => $chat_id,
            'text' => $question->question,
            'reply_markup' => json_encode([
                'inline_keyboard' => $keyboard
            ])
        ]);

        return "Here's your question. Choose an answer from the options below.";
    }

    private function evaluateAnswer($chat_id, $userAnswer)
    {
        $user = UserTelegram::where('telegram_chat_id', $chat_id)->first();

        if (!$user) {
            return "Sorry, you need to register first.";
        }

        $lastUnansweredLog = QuizLog::where('user_telegram_id', $user->id)
            ->where('is_answered', false)
            ->orderBy('created_at', 'asc')
            ->first();

        if (!$lastUnansweredLog) {
            return "Sorry, there's no active question to answer.";
        }

        $question = Question::find($lastUnansweredLog->question_id);

        if (!$question) {
            return "Sorry, there was an error retrieving the question.";
        }

        $isCorrect = $lastUnansweredLog->correct_answer === $userAnswer;

        $lastUnansweredLog->update([
            'user_answer' => $userAnswer,
            'is_correct' => $isCorrect,
            'is_answered' => true,
        ]);

        if ($isCorrect) {
            return "You're right! +100";
        } else {
            return "Sorry, that's incorrect. The correct answer was {$lastUnansweredLog->correct_answer}.";
        }
    }
}