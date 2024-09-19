<?php

namespace App\Helpers;

class ResponseHelper
{
    protected static $responses = [
        'welcome_back' => "Welcome back, :name! How can I assist you today?",
        'welcome_not_registered' => "Welcome! You're not registered yet. Please use /register to sign up.",
        'already_registered' => "You're already registered, :name!",
        'success_register' => "Thank you for registering, :name! You're now all set.",
 
        'echo' => ":name, you said :text",
        'please_register' => "Please register using /register to get personalized responses."
    ];

    public static function getResponse(string $key, array $params = []): string
    {
        if (!isset(static::$responses[$key])) {
            return "Response not found for key: {$key}";
        }

        $response = static::$responses[$key];

        foreach ($params as $param => $value) {
            $response = str_replace(":{$param}", $value, $response);
        }

        return $response;
    }
}