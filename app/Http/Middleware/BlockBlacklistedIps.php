<?php

namespace App\Http\Middleware;

use App\Models\IPBlacklist;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class BlockBlacklistedIps
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Schema::hasTable('ip_blacklists')) {
            $ip = $request->ip();
            $blocked = IPBlacklist::where('ip_address', $ip)
                ->where('is_active', true)
                ->where(function ($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
                })
                ->exists();
            if ($blocked) {
                abort(403, 'Access denied');
            }
        }

        return $next($request);
    }
}
