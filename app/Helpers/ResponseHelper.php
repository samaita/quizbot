<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ResponseHelper
{
    private static $responses = [];
    private static $defaultLanguage = 'en';

    public static function init()
    {
        self::$responses = self::loadResponses();
    }

    public static function getResponse(string $key, array $params = [], string $language = null): string
    {
        $language = $language ?? self::$defaultLanguage;

        if (!isset(self::$responses[$language][$key])) {
            return "Response not found for key: {$key} in language: {$language}";
        }

        $response = self::$responses[$language][$key];

        foreach ($params as $param => $value) {
            $response = str_replace(":{$param}", $value, $response);
        }

        return $response;
    }

    public static function setDefaultLanguage(string $language)
    {
        self::$defaultLanguage = $language;
    }

    private static function loadResponses(): array
    {
        return Cache::remember('all_responses', 3600, function () {
            $responses = DB::table('responses')->get();
            $groupedResponses = [];

            foreach ($responses as $response) {
                $groupedResponses[$response->language][$response->key] = $response->content;
            }

            return $groupedResponses;
        });
    }
}