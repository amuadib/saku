<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Telegram
{
    public static function send($message)
    {
        try {
            Http::timeout(2)->post("https://api.telegram.org/bot" . env('BOT_TOKEN') . "/sendMessage", [
                'chat_id' => env('CHAT_ID'),
                'text'    => $message,
                'parse_mode' => 'HTML',
            ]);
        } catch (\Exception $e) {
            Log::error("Gagal mengirim pesan ke Telegram. Reason: " . $e->getMessage());
        }
    }
}
