<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Models\SystemAlert;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EnforceDataRetention extends Command
{
    protected $signature = 'compliance:enforce-retention';

    protected $description = 'Purge old logs per data retention policy';

    public function handle(): int
    {
        $deleted = [
            'system_logs' => 0,
            'security_failed_logins' => 0,
            'search_logs' => 0,
        ];

        if (Schema::hasTable('system_logs')) {
            $deleted['system_logs'] = DB::table('system_logs')
                ->where('created_at', '<', now()->subDays(180))
                ->delete();
        }

        if (Schema::hasTable('security_audit_logs')) {
            $deleted['security_failed_logins'] = DB::table('security_audit_logs')
                ->where('event_type', 'login_attempt')
                ->where('status', 'failed')
                ->where('created_at', '<', now()->subDays(90))
                ->delete();
        }

        if (Schema::hasTable('performance_metrics')) {
            $deleted['search_logs'] = DB::table('performance_metrics')
                ->where('metric_name', 'search_zero_results')
                ->where('metric_type', 'daily')
                ->where('metric_date', '<', now()->subDays(180)->toDateString())
                ->delete();
        }

        AuditLog::create([
            'user_id' => null,
            'action' => 'data_retention_cleanup',
            'model_type' => 'compliance',
            'model_id' => null,
            'new_values' => $deleted,
            'ip_address' => null,
            'user_agent' => null,
            'metadata' => ['executed_at' => now()->toDateTimeString()],
        ]);

        SystemAlert::create([
            'title' => 'Data Retention Cleanup',
            'message' => 'Cleanup executed',
            'type' => 'compliance',
            'priority' => 'medium',
        ]);

        $this->info('Data retention cleanup completed');

        return Command::SUCCESS;
    }
}
