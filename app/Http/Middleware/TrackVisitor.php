<?php

namespace App\Http\Middleware;

use App\Models\Database\Visitor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    public function handle(Request $request, Closure $next): Response
    {
        if (
            $request->is('dashboard-admin') ||
            $request->is('dashboard-admin/*') ||
            $request->is('Get-Login-AdMiN') ||
            $request->is('Get-Login-AdMiN/post')
        ) {
            return $next($request);
        }

        $ip = $request->ip();

        try {
            $response = Http::timeout(3)
                ->get("http://ip-api.com/json/" . $ip);

            if ($response->successful() && $response['status'] === 'success') {
                $data = $response->json();
            } else {
                $data = [];
            }
        } catch (\Throwable $e) {
            $data = []; // fallback kalau timeout / lag
        }

        Visitor::create([
            'ip_address' => $ip,
            'user_agent' => $request->userAgent(),
            'country' => $data['country'] ?? 'Unknown',
            'province' => $data['regionName'] ?? 'Unknown',
            'city' => $data['city'] ?? 'Unknown',
            'isp' => $data['isp'] ?? 'Unknown',
            'url' => $request->url(),
        ]);

        return $next($request);
    }
}
