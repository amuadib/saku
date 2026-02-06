<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\Telegram;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class BlockBadBots
{
    public function handle(Request $request, Closure $next): Response
    {
        $forbiddenAgents = ['python-requests', 'curl', 'Go-http-client'];

        foreach ($forbiddenAgents as $agent) {
            if (str_contains($request->userAgent(), $agent) && $request->is('livewire/update')) {
                $ua = is_array($request->userAgent()) ? implode(',', $request->userAgent()) : $request->userAgent();

                Log::channel('attacks')->warning('Bot Attack Detected', [
                    'ip'         => $request->ip(),
                    'user_agent' => $ua,
                    'url'        => $request->fullUrl(),
                    'payload'    => $request->all(), // Mengambil semua data POST/JSON
                    'headers'    => $request->headers->all(),
                ]);
                Telegram::send(
                    "⚠️ <b>SECURITY ALERT: BOT DETECTED</b> ⚠️\n\n"
                        . "<b>Time:</b> " . now()->format('Y-m-d H:i:s') . "\n"
                        . "<b>IP Address:</b> {$request->ip()}\n"
                        . "<b>Target:</b> {$request->fullUrl()}\n"
                        . "<b>User Agent:</b> {$ua}\n\n"
                        . "<b>Payload:</b> " . json_encode($request->all()) . "\n\n"
                        . "🛡️ _Request has been replied with dummy JSON response._"
                );
                // abort(403, 'Unauthorized Bot Activity');
                usleep(rand(50000, 200000)); // random delay
                return response()->json([
                    'components' => [
                        [
                            'snapshot' => json_encode([
                                'memo' => [
                                    'id' => $request->input('components.0.snapshot.memo.id') ?? 'x-component-id',
                                    'name' => $request->input('components.0.snapshot.memo.name') ?? 'x-component-name',
                                ],
                            ]),
                            'effects' => [
                                'html' => null, // Kita tidak kirim HTML agar tidak memproses apapun
                                'returns' => [],
                            ],
                        ]
                    ],
                    'assets' => []
                ], 200);
            }
        }

        return $next($request);
    }
}
