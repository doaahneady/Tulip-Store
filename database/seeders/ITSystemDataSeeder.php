<?php

namespace Database\Seeders;

use App\Models\ScheduledTask;
use App\Models\SlowQuery;
use App\Models\SystemAlert;
use App\Models\SystemLog;
use App\Models\SystemService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ITSystemDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // System Services
        $services = [
            [
                'name' => 'web_server',
                'display_name' => 'Web Server (Apache/Nginx)',
                'status' => 'running',
                'uptime' => '15 يوم',
                'cpu_usage' => '12%',
                'memory_usage' => '340 MB',
                'port' => 80,
                'last_checked_at' => now(),
            ],
            [
                'name' => 'database',
                'display_name' => 'Database (MySQL)',
                'status' => 'running',
                'uptime' => '15 يوم',
                'cpu_usage' => '8%',
                'memory_usage' => '520 MB',
                'port' => 3306,
                'last_checked_at' => now(),
            ],
            [
                'name' => 'redis',
                'display_name' => 'Redis Cache',
                'status' => 'running',
                'uptime' => '15 يوم',
                'cpu_usage' => '3%',
                'memory_usage' => '128 MB',
                'port' => 6379,
                'last_checked_at' => now(),
            ],
            [
                'name' => 'queue_worker',
                'display_name' => 'Queue Worker',
                'status' => 'running',
                'uptime' => '2 يوم',
                'cpu_usage' => '5%',
                'memory_usage' => '85 MB',
                'port' => null,
                'last_checked_at' => now(),
            ],
        ];

        foreach ($services as $service) {
            SystemService::create($service);
        }

        // Scheduled Tasks
        $tasks = [
            [
                'name' => 'نسخ احتياطي يومي',
                'command' => 'backup:run',
                'schedule' => 'daily',
                'schedule_time' => '02:00',
                'status' => 'success',
                'last_run_at' => Carbon::now()->subHours(8),
                'next_run_at' => Carbon::now()->addHours(16),
                'run_count' => 45,
                'failure_count' => 0,
                'last_output' => 'Backup completed successfully',
                'is_enabled' => true,
            ],
            [
                'name' => 'تنظيف السجلات',
                'command' => 'logs:clean',
                'schedule' => 'weekly',
                'schedule_time' => '03:00',
                'status' => 'success',
                'last_run_at' => Carbon::now()->subDays(3),
                'next_run_at' => Carbon::now()->addDays(4),
                'run_count' => 12,
                'failure_count' => 0,
                'last_output' => 'Cleaned 250 MB of old logs',
                'is_enabled' => true,
            ],
            [
                'name' => 'تحديث الإحصائيات',
                'command' => 'stats:update',
                'schedule' => 'hourly',
                'schedule_time' => null,
                'status' => 'success',
                'last_run_at' => Carbon::now()->subMinutes(45),
                'next_run_at' => Carbon::now()->addMinutes(15),
                'run_count' => 720,
                'failure_count' => 2,
                'last_output' => 'Statistics updated',
                'is_enabled' => true,
            ],
            [
                'name' => 'فحص الأمان',
                'command' => 'security:scan',
                'schedule' => 'daily',
                'schedule_time' => '06:00',
                'status' => 'success',
                'last_run_at' => Carbon::now()->subHours(2),
                'next_run_at' => Carbon::now()->addHours(22),
                'run_count' => 30,
                'failure_count' => 1,
                'last_output' => 'Found 2 minor issues',
                'is_enabled' => true,
            ],
        ];

        foreach ($tasks as $task) {
            ScheduledTask::create($task);
        }

        // System Alerts
        $alerts = [
            [
                'type' => 'warning',
                'title' => 'استخدام المعالج مرتفع',
                'message' => 'استخدام المعالج وصل إلى 75%',
                'priority' => 'medium',
                'is_read' => false,
                'is_resolved' => false,
                'created_at' => Carbon::now()->subMinutes(5),
            ],
            [
                'type' => 'info',
                'title' => 'تحديث المنتجات',
                'message' => 'تم تحديث 15 منتج بنجاح',
                'priority' => 'low',
                'is_read' => false,
                'is_resolved' => false,
                'created_at' => Carbon::now()->subMinutes(15),
            ],
            [
                'type' => 'success',
                'title' => 'نسخة احتياطية',
                'message' => 'اكتملت النسخة الاحتياطية بنجاح',
                'priority' => 'low',
                'is_read' => true,
                'is_resolved' => true,
                'created_at' => Carbon::now()->subHour(),
            ],
            [
                'type' => 'error',
                'title' => 'فشل الاتصال بقاعدة البيانات',
                'message' => 'فشل الاتصال بقاعدة البيانات الاحتياطية',
                'priority' => 'high',
                'is_read' => true,
                'is_resolved' => true,
                'resolved_at' => Carbon::now()->subMinutes(30),
                'resolution_notes' => 'تم إعادة تشغيل الخدمة',
                'created_at' => Carbon::now()->subHours(2),
            ],
        ];

        foreach ($alerts as $alert) {
            SystemAlert::create($alert);
        }

        // Slow Queries
        $queries = [
            [
                'query' => 'SELECT * FROM orders WHERE created_at > ? AND status = ? ORDER BY total DESC',
                'execution_time' => 2.345,
                'call_count' => 45,
                'severity' => 'high',
                'database' => 'tulip_store',
                'table_name' => 'orders',
                'is_optimized' => false,
                'last_seen_at' => Carbon::now()->subMinutes(10),
            ],
            [
                'query' => 'SELECT * FROM products JOIN categories ON products.category_id = categories.id WHERE products.stock > 0',
                'execution_time' => 1.823,
                'call_count' => 120,
                'severity' => 'medium',
                'database' => 'tulip_store',
                'table_name' => 'products',
                'is_optimized' => false,
                'last_seen_at' => Carbon::now()->subMinutes(5),
            ],
            [
                'query' => 'UPDATE users SET last_login = ? WHERE id = ?',
                'execution_time' => 0.945,
                'call_count' => 230,
                'severity' => 'low',
                'database' => 'tulip_store',
                'table_name' => 'users',
                'is_optimized' => true,
                'optimized_at' => Carbon::now()->subDays(2),
                'optimization_notes' => 'Added index on last_login column',
                'last_seen_at' => Carbon::now()->subMinutes(2),
            ],
        ];

        foreach ($queries as $query) {
            SlowQuery::create($query);
        }

        // System Logs
        $logs = [
            [
                'level' => 'info',
                'action' => 'User Login',
                'message' => 'User logged in successfully',
                'user' => 'admin@tulipstore.com',
                'ip_address' => '192.168.1.100',
                'created_at' => Carbon::now()->subMinutes(5),
            ],
            [
                'level' => 'warning',
                'action' => 'Slow Query Detected',
                'message' => 'Query execution time exceeded 2 seconds',
                'user' => 'System',
                'ip_address' => '127.0.0.1',
                'created_at' => Carbon::now()->subMinutes(10),
            ],
            [
                'level' => 'info',
                'action' => 'Cache Cleared',
                'message' => 'Application cache cleared successfully',
                'user' => 'IT Supervisor',
                'ip_address' => '192.168.1.50',
                'created_at' => Carbon::now()->subHours(2),
            ],
            [
                'level' => 'error',
                'action' => 'Failed Login Attempt',
                'message' => 'Multiple failed login attempts detected',
                'user' => 'unknown',
                'ip_address' => '203.45.67.89',
                'created_at' => Carbon::now()->subHours(5),
            ],
            [
                'level' => 'info',
                'action' => 'Order Created',
                'message' => 'New order #ORD-12345 created',
                'user' => 'customer@example.com',
                'ip_address' => '192.168.1.200',
                'created_at' => Carbon::now()->subMinutes(30),
            ],
        ];

        foreach ($logs as $log) {
            SystemLog::create($log);
        }

        $this->command->info('IT System data seeded successfully!');
    }
}
