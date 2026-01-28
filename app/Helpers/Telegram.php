<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class Telegram
{
    public static function send($message)
    {
        Http::post("https://api.telegram.org/bot" . env('BOT_TOKEN') . "/sendMessage", [
            'chat_id' => env('CHAT_ID'),
            'text'    => $message,
            'parse_mode' => 'HTML',
        ]);
    }
}
