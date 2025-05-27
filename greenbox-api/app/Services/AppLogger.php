<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class AppLogger
{
    public static function info(string $message, array $context = [])
    {
        Log::info($message, $context);
    }

    public static function error(string $message, array $context = [])
    {
        Log::error($message, $context);
    }

    public static function warning(string $message, array $context = [])
    {
        Log::warning($message, $context);
    }

    public static function debug(string $message, array $context = [])
    {
        Log::debug($message, $context);
    }
}
