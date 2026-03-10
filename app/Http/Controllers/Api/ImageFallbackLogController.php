<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ImageFallbackLogController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|string|max:2000',
            'status' => 'nullable|integer|min:0|max:599',
            'error' => 'nullable|string|max:2000',
            'at' => 'nullable|string|max:64',
            'context' => 'nullable|string|max:100',
        ]);

        $payload = [
            'url' => $validated['url'],
            'status' => $validated['status'] ?? null,
            'error' => $validated['error'] ?? null,
            'timestamp' => $validated['at'] ?? now()->toIso8601String(),
            'context' => $validated['context'] ?? null,
            'ip' => $request->ip(),
            'ua' => substr((string) $request->userAgent(), 0, 255),
        ];

        if (class_exists(SystemLog::class) && Schema::hasTable('system_logs')) {
            $row = [
                'type' => 'image_fallback',
                'message' => 'Image load failed, fallback attempted',
                'context' => $payload,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            foreach (array_keys($row) as $k) {
                if (! Schema::hasColumn('system_logs', $k)) {
                    unset($row[$k]);
                }
            }
            try {
                SystemLog::create($row);
            } catch (\Throwable $e) {
                \Log::warning('image_fallback', $payload);
            }
        } else {
            \Log::warning('image_fallback', $payload);
        }

        return response()->json(['success' => true]);
    }
}

