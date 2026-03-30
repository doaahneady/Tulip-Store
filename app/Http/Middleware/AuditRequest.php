<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuditRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $webUser = Auth::guard('web')->user();
        if (! $webUser) {
            return $response;
        }

        $method = strtoupper($request->method());
        if (in_array($method, ['GET', 'HEAD', 'OPTIONS'], true)) {
            return $response;
        }

        $routeName = $request->route()?->getName();
        $action = $routeName ? ($method.':'.$routeName) : $method;

        $excludedKeys = [
            '_token',
            'password',
            'password_confirmation',
            'current_password',
            'token',
            'code',
            'otp',
        ];

        $input = $request->except($excludedKeys);

        foreach ($request->allFiles() as $key => $file) {
            unset($input[$key]);
        }

        $newValues = [
            'method' => $method,
            'path' => $request->path(),
            'status' => $response->getStatusCode(),
            'input' => $this->sanitize($input),
        ];

        AuditLog::log($action, null, null, $newValues, [
            'route' => $routeName,
        ]);

        return $response;
    }

    private function sanitize($value)
    {
        if (is_null($value) || is_bool($value) || is_int($value) || is_float($value)) {
            return $value;
        }

        if (is_string($value)) {
            return mb_strlen($value) > 500 ? (mb_substr($value, 0, 500).'...') : $value;
        }

        if (is_array($value)) {
            $out = [];
            $count = 0;
            foreach ($value as $k => $v) {
                $count++;
                if ($count > 50) {
                    $out['__truncated__'] = true;
                    break;
                }
                $out[$k] = $this->sanitize($v);
            }

            return $out;
        }

        return (string) $value;
    }
}

