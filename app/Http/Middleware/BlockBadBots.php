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
        $jails=['ssh', 'plesk-apache', 'plesk-permanent-ban'];

        foreach ($forbiddenAgents as $agent) {
            if (str_contains($request->userAgent() ?? '', $agent) && $request->is('livewire/update')) {
                $ua = is_array($request->userAgent()) ? implode(',', $request->userAgent()) : $request->userAgent();
                $ip = $request->ip();

                $execDisabled = !function_exists('exec') || in_array('exec', array_map('trim', explode(',', ini_get('disable_functions'))));

                $banResult = "";
                foreach ($jails as $jail) {
                    if ($execDisabled) {
                        $banResult .= "⚠️ $jail : SKIPPED (exec() is disabled on this server)\n";
                    } else {
                        exec("fail2ban-client set " . escapeshellarg($jail) . " banip " . escapeshellarg($ip) . " >/dev/null 2>&1", $output, $resultCode);
                        $banResult .= ($resultCode === 0 ? "✅ $jail : BANNED" : "❌ $jail : FAILED") . "\n";
                    }
                }

                $fp = fopen('/var/log/ssh_attack_stats.log', 'a');
                if ($fp !== false) {
                    flock($fp, LOCK_EX);
                    fwrite($fp, date('Y-m-d H:i:s') . " $ip \"livewire/update vulnerability attack\" \n");
                    flock($fp, LOCK_UN);
                    fclose($fp);
                }
                
                Log::channel('attacks')->warning('Bot Attack Detected', [
                    'ip'         => $ip,
                    'user_agent' => $ua,
                    'url'        => $request->fullUrl(),
                    'payload'    => $request->all(), // Mengambil semua data POST/JSON
                    'headers'    => $request->headers->all(),
                    'ban_status' => $banResult,
                ]);
                Telegram::send(
                    "⚠️ <b>SECURITY ALERT: BOT DETECTED</b> ⚠️\n\n"
                        . "<b>Time:</b> " . now()->format('Y-m-d H:i:s') . "\n"
                        . "<b>IP Address:</b> ".$ip."\n"
                        . "<b>Target:</b> ".$request->fullUrl()."\n"
                        . "<b>User Agent:</b> ".$ua."\n\n"
                        . "<b>Payload:</b> ".json_encode($request->all())."\n\n"
                        . "🛡️ _Request has been replied with dummy JSON response._"
                        . "\n\n"
                        . "<b>Fail2ban Status:</b>\n"
                        . $banResult
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
