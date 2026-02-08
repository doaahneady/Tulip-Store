<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\DeliveryAssignment;
use App\Models\Driver;
use App\Models\DriverLocation;
use App\Models\Employee;
use App\Models\EmployeeAttendance;
use App\Models\EnhancedAuditLog;
use App\Models\EnhancedFinancialTransaction;
use App\Models\EnhancedSupportTicket;
use App\Models\EnhancedSupportTicketReply;
use App\Models\FinancialTransaction;
use App\Models\InventoryMovement;
use App\Models\LeaveRequest;
use App\Models\Order;
use App\Models\PayrollRecord;
use App\Models\Permission;
use App\Models\Product;
use App\Models\Role;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use App\Models\SystemAlert;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Database\Seeder;

class ComprehensiveDashboardSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSystemSettings();
        $this->seedSystemAlerts();
        $this->seedRolesAndPermissions();
        $this->seedEnhancedFinancialTransactions();
        $this->seedEnhancedSupportTickets();
        $this->seedInventoryMovements();
        $this->seedDriverLocations();
        $this->seedDeliveryAssignments();
        $this->seedEmployeeAttendance();
        $this->seedLeaveRequests();
        $this->seedPayrollRecords();
        $this->seedEnhancedAuditLogs();
    }

    private function seedEnhancedFinancialTransactions()
    {
        $orders = Order::take(15)->get();
        $users = User::take(10)->get();
        $employees = Employee::take(3)->get();

        foreach ($orders as $order) {
            // Payment transaction
            EnhancedFinancialTransaction::create([
                'transaction_id' => EnhancedFinancialTransaction::generateTransactionId('payment'),
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'store_id' => $order->store_id ?? null,
                'type' => 'payment',
                'amount' => $order->total_amount ?? $order->total ?? rand(50, 500),
                'currency' => 'USD',
                'status' => 'completed',
                'payment_method' => ['credit_card', 'paypal', 'bank_transfer'][rand(0, 2)],
                'gateway' => ['stripe', 'paypal', 'square'][rand(0, 2)],
                'gateway_transaction_id' => 'gw_'.time().'_'.rand(1000, 9999),
                'description' => "Payment for order #{$order->order_number}",
                'processed_by' => $employees->random()->id,
                'processed_at' => $order->created_at,
            ]);

            // Commission transaction (5% of order value)
            if (rand(1, 2) === 1) {
                $orderAmount = $order->total_amount ?? $order->total ?? rand(50, 500);
                EnhancedFinancialTransaction::create([
                    'transaction_id' => EnhancedFinancialTransaction::generateTransactionId('commission'),
                    'order_id' => $order->id,
                    'store_id' => $order->store_id ?? null,
                    'type' => 'commission',
                    'amount' => $orderAmount * 0.05,
                    'currency' => 'USD',
                    'status' => 'completed',
                    'description' => "Commission for order #{$order->order_number}",
                    'processed_by' => $employees->random()->id,
                    'processed_at' => $order->created_at->addMinutes(5),
                ]);
            }
        }

        // Add some refund transactions
        $refundOrders = $orders->take(3);
        foreach ($refundOrders as $order) {
            $orderAmount = $order->total_amount ?? $order->total ?? rand(50, 500);
            EnhancedFinancialTransaction::create([
                'transaction_id' => EnhancedFinancialTransaction::generateTransactionId('refund'),
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'store_id' => $order->store_id ?? null,
                'type' => 'refund',
                'amount' => $orderAmount,
                'currency' => 'USD',
                'status' => 'completed',
                'payment_method' => 'credit_card',
                'gateway' => 'stripe',
                'description' => "Refund for order #{$order->order_number}",
                'processed_by' => $employees->random()->id,
                'processed_at' => now()->subDays(rand(1, 7)),
            ]);
        }

        // Add some fee transactions
        for ($i = 0; $i < 5; $i++) {
            EnhancedFinancialTransaction::create([
                'transaction_id' => EnhancedFinancialTransaction::generateTransactionId('fee'),
                'type' => 'fee',
                'amount' => rand(10, 50),
                'currency' => 'USD',
                'status' => 'completed',
                'description' => 'Payment processing fee',
                'processed_by' => $employees->random()->id,
                'processed_at' => now()->subDays(rand(1, 30)),
            ]);
        }
    }

    private function seedEnhancedSupportTickets()
    {
        $users = User::take(15)->get();
        $employees = Employee::take(5)->get();

        $tickets = [
            [
                'subject' => 'Unable to complete payment',
                'description' => 'I am trying to place an order but the payment keeps failing. I have tried multiple cards and different browsers.',
                'priority' => 'high',
                'category' => 'payment',
                'status' => 'open',
            ],
            [
                'subject' => 'Product not delivered',
                'description' => 'I ordered a product 5 days ago but it has not been delivered yet. The tracking shows it is still in processing.',
                'priority' => 'medium',
                'category' => 'delivery',
                'status' => 'in_progress',
            ],
            [
                'subject' => 'Account login issues',
                'description' => 'I cannot log into my account. I have tried resetting my password but I am not receiving the email.',
                'priority' => 'medium',
                'category' => 'account',
                'status' => 'waiting_customer',
            ],
            [
                'subject' => 'Refund request for damaged item',
                'description' => 'I would like to return a product I purchased last week. It arrived damaged and does not match the description.',
                'priority' => 'low',
                'category' => 'refund',
                'status' => 'resolved',
            ],
            [
                'subject' => 'Website performance issues',
                'description' => 'The website is loading very slowly and sometimes times out completely. This is affecting my shopping experience.',
                'priority' => 'urgent',
                'category' => 'technical',
                'status' => 'open',
            ],
            [
                'subject' => 'Wrong item received',
                'description' => 'I ordered a blue shirt size M but received a red shirt size L. Please help me exchange this.',
                'priority' => 'medium',
                'category' => 'order',
                'status' => 'in_progress',
            ],
            [
                'subject' => 'Billing inquiry',
                'description' => 'I see a charge on my credit card that I do not recognize. Can you help me identify this transaction?',
                'priority' => 'high',
                'category' => 'billing',
                'status' => 'waiting_customer',
            ],
        ];

        foreach ($tickets as $index => $ticketData) {
            $user = $users->random();
            $employee = $employees->random();

            $ticket = EnhancedSupportTicket::create([
                'ticket_number' => EnhancedSupportTicket::generateTicketNumber(),
                'user_id' => $user->id,
                'assigned_to' => $ticketData['status'] !== 'open' ? $employee->id : null,
                'subject' => $ticketData['subject'],
                'description' => $ticketData['description'],
                'priority' => $ticketData['priority'],
                'status' => $ticketData['status'],
                'category' => $ticketData['category'],
                'tags' => ['customer_service', $ticketData['category']],
                'first_response_at' => $ticketData['status'] !== 'open' ? now()->subHours(rand(1, 24)) : null,
                'resolved_at' => $ticketData['status'] === 'resolved' ? now()->subDays(rand(1, 3)) : null,
            ]);

            // Add some replies
            if ($ticketData['status'] !== 'open') {
                EnhancedSupportTicketReply::create([
                    'ticket_id' => $ticket->id,
                    'author_type' => 'App\\Models\\Employee',
                    'author_id' => $employee->id,
                    'message' => 'Thank you for contacting us. We are looking into your issue and will get back to you shortly with a solution.',
                    'is_internal' => false,
                ]);

                if ($ticketData['status'] === 'resolved') {
                    EnhancedSupportTicketReply::create([
                        'ticket_id' => $ticket->id,
                        'author_type' => 'App\\Models\\Employee',
                        'author_id' => $employee->id,
                        'message' => 'This issue has been resolved. We have processed your request and you should see the changes reflected in your account. Please let us know if you need any further assistance.',
                        'is_internal' => false,
                    ]);
                }

                // Add customer response for some tickets
                if (rand(1, 3) === 1) {
                    EnhancedSupportTicketReply::create([
                        'ticket_id' => $ticket->id,
                        'author_type' => 'App\\Models\\User',
                        'author_id' => $user->id,
                        'message' => 'Thank you for the quick response. I appreciate your help with this matter.',
                        'is_internal' => false,
                    ]);
                }
            }
        }
    }

    private function seedEnhancedAuditLogs()
    {
        $employees = Employee::all();
        $users = User::take(15)->get();

        $actions = [
            'login', 'logout', 'create', 'update', 'delete', 'view',
            'password_change', 'profile_update', 'order_placed', 'payment_processed',
        ];

        // Create 150 audit log entries
        for ($i = 0; $i < 150; $i++) {
            $isEmployee = rand(0, 1);
            $user = $isEmployee ? $employees->random() : $users->random();
            $action = $actions[rand(0, count($actions) - 1)];

            EnhancedAuditLog::create([
                'user_id' => $user->id,
                'user_type' => $isEmployee ? 'App\\Models\\Employee' : 'App\\Models\\User',
                'action' => $action,
                'model_type' => $this->getModelTypeForAction($action),
                'model_id' => rand(1, 100),
                'old_values' => $action === 'update' ? ['status' => 'pending', 'amount' => '100.00'] : null,
                'new_values' => $action === 'update' ? ['status' => 'completed', 'amount' => '150.00'] : ($action === 'create' ? ['name' => 'New Item', 'status' => 'active'] : null),
                'ip_address' => $this->generateRandomIP(),
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                'metadata' => [
                    'session_id' => 'sess_'.rand(100000, 999999),
                    'request_method' => ['GET', 'POST', 'PUT', 'DELETE'][rand(0, 3)],
                    'request_url' => '/dashboard/'.strtolower($action),
                ],
                'created_at' => now()->subDays(rand(0, 60))->subHours(rand(0, 23))->subMinutes(rand(0, 59)),
            ]);
        }
    }

    private function seedSystemSettings()
    {
        $settings = [
            // General Settings
            ['key' => 'site.name', 'value' => 'Tulip Store', 'description' => 'Site name'],
            ['key' => 'site.description', 'value' => 'Premium E-commerce Platform', 'description' => 'Site description'],
            ['key' => 'site.logo', 'value' => '/images/logo.png', 'description' => 'Site logo path'],
            ['key' => 'site.timezone', 'value' => 'UTC', 'description' => 'Default timezone'],

            // Maintenance Settings
            ['key' => 'maintenance.mode', 'value' => 'false', 'type' => 'boolean', 'description' => 'Maintenance mode status'],
            ['key' => 'maintenance.message', 'value' => 'Site is under maintenance', 'description' => 'Maintenance message'],

            // Email Settings
            ['key' => 'email.from_name', 'value' => 'Tulip Store', 'description' => 'Default from name'],
            ['key' => 'email.from_address', 'value' => 'noreply@tulipstore.com', 'description' => 'Default from address'],

            // Payment Settings
            ['key' => 'payment.currency', 'value' => 'USD', 'description' => 'Default currency'],
            ['key' => 'payment.commission_rate', 'value' => '5.0', 'type' => 'string', 'description' => 'Commission rate percentage'],

            // Security Settings
            ['key' => 'security.max_login_attempts', 'value' => '5', 'type' => 'string', 'description' => 'Maximum login attempts'],
            ['key' => 'security.lockout_duration', 'value' => '15', 'type' => 'string', 'description' => 'Lockout duration in minutes'],
            ['key' => 'security.session_timeout', 'value' => '120', 'type' => 'string', 'description' => 'Session timeout in minutes'],

            // Backup Settings
            ['key' => 'backup.auto_backup', 'value' => 'true', 'type' => 'boolean', 'description' => 'Enable automatic backups'],
            ['key' => 'backup.frequency', 'value' => 'daily', 'description' => 'Backup frequency'],
            ['key' => 'backup.retention_days', 'value' => '30', 'type' => 'string', 'description' => 'Backup retention in days'],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    private function seedSystemAlerts()
    {
        $alerts = [
            [
                'title' => 'Low Stock Alert',
                'message' => '15 products are running low on stock and need restocking.',
                'type' => 'warning',
                'priority' => 'medium',
                'is_read' => false,
                'is_resolved' => false,
            ],
            [
                'title' => 'High Server Load',
                'message' => 'Server CPU usage is above 85% for the last 10 minutes.',
                'type' => 'error',
                'priority' => 'high',
                'is_read' => false,
                'is_resolved' => false,
            ],
            [
                'title' => 'Payment Gateway Issue',
                'message' => 'Payment gateway is experiencing intermittent failures.',
                'type' => 'error',
                'priority' => 'critical',
                'is_read' => false,
                'is_resolved' => false,
            ],
            [
                'title' => 'Backup Completed',
                'message' => 'Daily backup completed successfully at 2:00 AM.',
                'type' => 'success',
                'priority' => 'low',
                'is_read' => true,
                'is_resolved' => true,
                'resolved_by' => 1,
                'resolved_at' => now()->subHours(2),
                'resolution_notes' => 'Backup completed successfully',
            ],
            [
                'title' => 'New User Registration Spike',
                'message' => 'Unusual increase in user registrations detected (200% above normal).',
                'type' => 'info',
                'priority' => 'medium',
                'is_read' => false,
                'is_resolved' => false,
            ],
        ];

        foreach ($alerts as $alert) {
            SystemAlert::create($alert);
        }
    }

    private function seedRolesAndPermissions()
    {
        // Create Permissions
        $permissions = [
            // User Management
            ['name' => 'users.view', 'display_name' => 'View Users', 'description' => 'View user accounts and profiles'],
            ['name' => 'users.create', 'display_name' => 'Create Users', 'description' => 'Create new user accounts'],
            ['name' => 'users.edit', 'display_name' => 'Edit Users', 'description' => 'Edit existing user accounts'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'description' => 'Delete user accounts'],

            // Order Management
            ['name' => 'orders.view', 'display_name' => 'View Orders', 'description' => 'View order information'],
            ['name' => 'orders.edit', 'display_name' => 'Edit Orders', 'description' => 'Edit order details'],
            ['name' => 'orders.cancel', 'display_name' => 'Cancel Orders', 'description' => 'Cancel orders'],
            ['name' => 'orders.refund', 'display_name' => 'Refund Orders', 'description' => 'Process order refunds'],

            // Product Management
            ['name' => 'products.view', 'display_name' => 'View Products', 'description' => 'View product catalog'],
            ['name' => 'products.create', 'display_name' => 'Create Products', 'description' => 'Add new products'],
            ['name' => 'products.edit', 'display_name' => 'Edit Products', 'description' => 'Edit product information'],
            ['name' => 'products.delete', 'display_name' => 'Delete Products', 'description' => 'Remove products'],

            // Financial Management
            ['name' => 'finance.view', 'display_name' => 'View Financial Data', 'description' => 'View financial reports and data'],
            ['name' => 'finance.transactions', 'display_name' => 'Manage Transactions', 'description' => 'Manage financial transactions'],
            ['name' => 'finance.reports', 'display_name' => 'View Financial Reports', 'description' => 'Access financial reports'],

            // System Administration
            ['name' => 'system.settings', 'display_name' => 'Manage System Settings', 'description' => 'Configure system settings'],
            ['name' => 'system.backups', 'display_name' => 'Manage Backups', 'description' => 'Create and manage backups'],
            ['name' => 'system.logs', 'display_name' => 'View System Logs', 'description' => 'Access system logs'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        // Create Roles
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Administrator',
                'description' => 'Full system access',
                'permissions' => Permission::all()->pluck('id')->toArray(),
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Administrative access',
                'permissions' => Permission::whereIn('name', ['users.view', 'users.create', 'users.edit', 'orders.view', 'orders.edit', 'products.view', 'products.create', 'products.edit'])->pluck('id')->toArray(),
            ],
            [
                'name' => 'manager',
                'display_name' => 'Manager',
                'description' => 'Management access',
                'permissions' => Permission::whereIn('name', ['orders.view', 'orders.edit', 'products.view', 'products.edit'])->pluck('id')->toArray(),
            ],
            [
                'name' => 'customer',
                'display_name' => 'Customer',
                'description' => 'Customer access',
                'permissions' => [],
            ],
        ];

        foreach ($roles as $roleData) {
            $role = Role::updateOrCreate(
                ['name' => $roleData['name']],
                [
                    'display_name' => $roleData['display_name'],
                    'description' => $roleData['description'],
                ]
            );

            // Attach permissions using the pivot table
            if (! empty($roleData['permissions'])) {
                foreach ($roleData['permissions'] as $permissionId) {
                    \DB::table('permission_role')->insertOrIgnore([
                        'role_id' => $role->id,
                        'permission_id' => $permissionId,
                    ]);
                }
            }
        }
    }

    private function seedFinancialTransactions()
    {
        $orders = Order::take(10)->get();
        $users = User::take(5)->get();

        foreach ($orders as $order) {
            // Payment transaction
            FinancialTransaction::create([
                'transaction_id' => 'PAY_'.time().'_'.rand(1000, 9999),
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'store_id' => $order->store_id ?? null,
                'type' => 'order_payment',
                'amount' => $order->total,
                'status' => 'completed',
                'description' => "Payment for order #{$order->order_number}",
                'approval_status' => 'approved',
                'approved_by' => 1,
                'approved_at' => $order->created_at,
            ]);

            // Commission transaction
            FinancialTransaction::create([
                'transaction_id' => 'COM_'.time().'_'.rand(1000, 9999),
                'order_id' => $order->id,
                'store_id' => $order->store_id ?? null,
                'type' => 'commission',
                'amount' => $order->total * 0.05, // 5% commission
                'status' => 'completed',
                'description' => "Commission for order #{$order->order_number}",
                'approval_status' => 'approved',
                'approved_by' => 1,
                'approved_at' => $order->created_at->addMinutes(5),
            ]);
        }

        // Add some refund transactions
        $refundOrders = $orders->take(2);
        foreach ($refundOrders as $order) {
            FinancialTransaction::create([
                'transaction_id' => 'REF_'.time().'_'.rand(1000, 9999),
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'store_id' => $order->store_id ?? null,
                'type' => 'refund',
                'amount' => $order->total,
                'status' => 'completed',
                'description' => "Refund for order #{$order->order_number}",
                'approval_status' => 'approved',
                'approved_by' => 1,
                'approved_at' => now()->subDays(rand(1, 7)),
            ]);
        }
    }

    private function seedSupportTickets()
    {
        $users = User::take(10)->get();
        $employees = Employee::take(3)->get();

        $tickets = [
            [
                'subject' => 'Unable to complete payment',
                'description' => 'I am trying to place an order but the payment keeps failing. I have tried multiple cards.',
                'priority' => 'high',
                'category' => 'payment',
                'status' => 'open',
            ],
            [
                'subject' => 'Product not delivered',
                'description' => 'I ordered a product 5 days ago but it has not been delivered yet. The tracking shows it is still in processing.',
                'priority' => 'medium',
                'category' => 'delivery',
                'status' => 'in_progress',
            ],
            [
                'subject' => 'Account login issues',
                'description' => 'I cannot log into my account. I have tried resetting my password but I am not receiving the email.',
                'priority' => 'medium',
                'category' => 'account',
                'status' => 'waiting_customer',
            ],
            [
                'subject' => 'Refund request',
                'description' => 'I would like to return a product I purchased last week. It does not match the description.',
                'priority' => 'low',
                'category' => 'refund',
                'status' => 'resolved',
            ],
            [
                'subject' => 'Website performance issues',
                'description' => 'The website is loading very slowly and sometimes times out completely.',
                'priority' => 'urgent',
                'category' => 'technical',
                'status' => 'open',
            ],
        ];

        foreach ($tickets as $index => $ticketData) {
            $user = $users->random();
            $employee = $employees->random();

            $ticket = SupportTicket::create([
                'ticket_number' => SupportTicket::generateTicketNumber(),
                'user_id' => $user->id,
                'assigned_to' => $ticketData['status'] !== 'open' ? $employee->id : null,
                'subject' => $ticketData['subject'],
                'description' => $ticketData['description'],
                'priority' => $ticketData['priority'],
                'status' => $ticketData['status'],
                'category' => $ticketData['category'],
                'first_response_at' => $ticketData['status'] !== 'open' ? now()->subHours(rand(1, 24)) : null,
                'resolved_at' => $ticketData['status'] === 'resolved' ? now()->subDays(rand(1, 3)) : null,
            ]);

            // Add some replies
            if ($ticketData['status'] !== 'open') {
                SupportTicketReply::create([
                    'ticket_id' => $ticket->id,
                    'author_type' => 'App\\Models\\Employee',
                    'author_id' => $employee->id,
                    'message' => 'Thank you for contacting us. We are looking into your issue and will get back to you shortly.',
                    'is_internal' => false,
                ]);

                if ($ticketData['status'] === 'resolved') {
                    SupportTicketReply::create([
                        'ticket_id' => $ticket->id,
                        'author_type' => 'App\\Models\\Employee',
                        'author_id' => $employee->id,
                        'message' => 'This issue has been resolved. Please let us know if you need any further assistance.',
                        'is_internal' => false,
                    ]);
                }
            }
        }
    }

    private function seedInventoryMovements()
    {
        $products = Product::take(20)->get();
        $employees = Employee::take(3)->get();

        foreach ($products as $product) {
            // Initial stock in
            InventoryMovement::create([
                'product_id' => $product->id,
                'type' => 'in',
                'quantity' => rand(50, 200),
                'previous_stock' => 0,
                'new_stock' => rand(50, 200),
                'reason' => 'Initial stock',
                'created_by' => $employees->random()->id,
                'created_at' => now()->subDays(rand(30, 90)),
            ]);

            // Some sales (out)
            for ($i = 0; $i < rand(3, 8); $i++) {
                $currentStock = $product->stock_quantity ?? rand(10, 100);
                $soldQuantity = rand(1, 5);

                InventoryMovement::create([
                    'product_id' => $product->id,
                    'type' => 'out',
                    'quantity' => $soldQuantity,
                    'previous_stock' => $currentStock,
                    'new_stock' => max(0, $currentStock - $soldQuantity),
                    'reason' => 'Sale',
                    'created_by' => $employees->random()->id,
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            }

            // Some adjustments
            if (rand(1, 3) === 1) {
                $currentStock = $product->stock_quantity ?? rand(10, 100);
                $adjustment = rand(-5, 10);

                InventoryMovement::create([
                    'product_id' => $product->id,
                    'type' => 'adjustment',
                    'quantity' => $adjustment,
                    'previous_stock' => $currentStock,
                    'new_stock' => max(0, $currentStock + $adjustment),
                    'reason' => $adjustment > 0 ? 'Stock correction - found additional items' : 'Stock correction - damaged items removed',
                    'notes' => 'Inventory audit adjustment',
                    'created_by' => $employees->random()->id,
                    'created_at' => now()->subDays(rand(1, 15)),
                ]);
            }
        }
    }

    private function seedDriverLocations()
    {
        $drivers = Driver::take(10)->get();

        foreach ($drivers as $driver) {
            // Create location history for the last 7 days
            for ($day = 0; $day < 7; $day++) {
                $date = now()->subDays($day);

                // Create multiple location points throughout the day
                for ($hour = 8; $hour < 18; $hour++) {
                    $latitude = 33.5138 + (rand(-100, 100) / 1000); // Damascus area
                    $longitude = 36.2765 + (rand(-100, 100) / 1000);

                    DriverLocation::create([
                        'driver_id' => $driver->id,
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'accuracy' => rand(5, 20),
                        'speed' => rand(0, 60),
                        'address' => 'Damascus, Syria',
                        'recorded_at' => $date->copy()->setHour($hour)->setMinute(rand(0, 59)),
                    ]);
                }
            }
        }
    }

    private function seedDeliveryAssignments()
    {
        $orders = Order::take(15)->get();
        $drivers = Driver::take(5)->get();
        $employees = Employee::take(2)->get(); // Just get any employees instead of filtering by role

        foreach ($orders as $order) {
            $driver = $drivers->random();
            $supervisor = $employees->random();

            $statuses = ['assigned', 'picked_up', 'in_transit', 'delivered'];
            $status = $statuses[rand(0, count($statuses) - 1)];

            $assignment = DeliveryAssignment::create([
                'order_id' => $order->id,
                'driver_id' => $driver->id,
                'status' => $status,
                'assigned_at' => $order->created_at->addMinutes(rand(30, 120)),
                'notes' => 'Delivery assignment for order #'.$order->order_number,
            ]);

            // Update timestamps based on status
            $currentTime = $assignment->assigned_at;

            if (in_array($status, ['picked_up', 'in_transit', 'delivered'])) {
                $assignment->update(['picked_up_at' => $currentTime->addMinutes(rand(15, 45))]);
                $currentTime = $assignment->picked_up_at;
            }

            if ($status === 'delivered') {
                $assignment->update([
                    'delivered_at' => $currentTime->addMinutes(rand(30, 120)),
                    'delivery_latitude' => 33.5138 + (rand(-10, 10) / 100),
                    'delivery_longitude' => 36.2765 + (rand(-10, 10) / 100),
                    'notes' => 'Package delivered successfully to recipient.',
                    'customer_signature' => 'signature_'.$order->id.'.png',
                ]);
            }
        }
    }

    private function seedEmployeeAttendance()
    {
        $employees = Employee::all();

        foreach ($employees as $employee) {
            // Create attendance for the last 30 days
            for ($day = 0; $day < 30; $day++) {
                $date = now()->subDays($day)->toDateString();

                // Skip weekends (assuming Saturday and Sunday are weekends)
                $dayOfWeek = now()->subDays($day)->dayOfWeek;
                if ($dayOfWeek === 0 || $dayOfWeek === 6) {
                    continue;
                }

                // Skip if attendance already exists for this employee and date
                if (EmployeeAttendance::where('employee_id', $employee->id)->where('date', $date)->exists()) {
                    continue;
                }

                $status = 'present';
                $clockIn = now()->subDays($day)->setHour(rand(8, 9))->setMinute(rand(0, 59));
                $clockOut = $clockIn->copy()->addHours(8)->addMinutes(rand(-30, 60));

                // Randomly make some days absent or late
                $random = rand(1, 10);
                if ($random === 1) {
                    $status = 'absent';
                    $clockIn = null;
                    $clockOut = null;
                } elseif ($random === 2) {
                    $status = 'sick_leave';
                    $clockIn = null;
                    $clockOut = null;
                } elseif ($random === 3) {
                    $status = 'late';
                    $clockIn = now()->subDays($day)->setHour(rand(9, 10))->setMinute(rand(0, 59));
                    $clockOut = $clockIn->copy()->addHours(8);
                }

                $totalHours = $clockIn && $clockOut ? $clockIn->diffInHours($clockOut) : 0;

                EmployeeAttendance::create([
                    'employee_id' => $employee->id,
                    'date' => $date,
                    'clock_in' => $clockIn,
                    'clock_out' => $clockOut,
                    'break_minutes' => rand(30, 60),
                    'total_hours' => $totalHours,
                    'status' => $status,
                    'notes' => $status === 'late' ? 'Traffic delay' : null,
                ]);
            }
        }
    }

    private function seedLeaveRequests()
    {
        $employees = Employee::all();

        foreach ($employees as $employee) {
            // Create 1-3 leave requests per employee
            for ($i = 0; $i < rand(1, 3); $i++) {
                $types = ['annual', 'sick', 'emergency', 'unpaid', 'maternity', 'paternity'];
                $type = $types[rand(0, count($types) - 1)];

                $startDate = now()->addDays(rand(-30, 60));
                $days = rand(1, 5);
                $endDate = $startDate->copy()->addDays($days - 1);

                $statuses = ['pending', 'approved', 'rejected'];
                $status = $statuses[rand(0, count($statuses) - 1)];

                LeaveRequest::create([
                    'employee_id' => $employee->id,
                    'leave_type' => $type,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'days_count' => $days,
                    'reason' => $this->getLeaveReason($type),
                    'status' => $status,
                    'approved_by' => $status !== 'pending' ? Employee::first()?->id : null,
                    'approved_at' => $status !== 'pending' ? now()->subDays(rand(1, 10)) : null,
                    'rejection_reason' => $status === 'rejected' ? 'Insufficient leave balance' : null,
                ]);
            }
        }
    }

    private function seedPayrollRecords()
    {
        $employees = Employee::all();

        // Create payroll for the last 3 months
        for ($month = 1; $month <= 3; $month++) {
            $period = now()->subMonths($month)->format('Y-m');

            foreach ($employees as $employee) {
                $baseSalary = rand(2000, 5000);
                $overtimeHours = rand(0, 20);
                $overtimeRate = rand(20, 50);
                $overtimePay = $overtimeHours * $overtimeRate;
                $bonuses = rand(0, 1) ? rand(100, 500) : 0;
                $commissions = rand(0, 1) ? rand(50, 300) : 0;
                $deductions = rand(50, 200);
                $grossPay = $baseSalary + $overtimePay + $bonuses + $commissions;
                $taxDeductions = $grossPay * 0.15;
                $netPay = $grossPay - $deductions - $taxDeductions;

                PayrollRecord::create([
                    'employee_id' => $employee->id,
                    'pay_period' => $period,
                    'base_salary' => $baseSalary,
                    'overtime_hours' => $overtimeHours,
                    'overtime_rate' => $overtimeRate,
                    'overtime_pay' => $overtimePay,
                    'bonuses' => $bonuses,
                    'commissions' => $commissions,
                    'deductions' => $deductions,
                    'gross_pay' => $grossPay,
                    'tax_deductions' => $taxDeductions,
                    'net_pay' => $netPay,
                    'status' => $month > 1 ? 'paid' : 'approved',
                    'processed_by' => Employee::first()?->id,
                    'processed_at' => now()->subMonths($month)->endOfMonth(),
                ]);
            }
        }
    }

    private function seedAuditLogs()
    {
        $employees = Employee::all();
        $users = User::take(10)->get();

        $actions = [
            'login', 'logout', 'create', 'update', 'delete', 'view',
            'password_change', 'profile_update', 'order_placed', 'payment_processed',
        ];

        // Create 100 audit log entries
        for ($i = 0; $i < 100; $i++) {
            $isEmployee = rand(0, 1);
            $user = $isEmployee ? $employees->random() : $users->random();
            $action = $actions[rand(0, count($actions) - 1)];

            $payload = [
                'user_id' => $isEmployee ? null : $user->id,
                'action' => $action,
                'model_type' => $this->getModelTypeForAction($action),
                'model_id' => rand(1, 100),
                'old_values' => $action === 'update' ? ['status' => 'pending'] : null,
                'new_values' => $action === 'update' ? ['status' => 'completed'] : ($action === 'create' ? ['name' => 'New Item'] : null),
                'ip_address' => $this->generateRandomIP(),
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59)),
            ];
            if (\Illuminate\Support\Facades\Schema::hasColumn('audit_logs', 'user_type')) {
                $payload['user_type'] = $isEmployee ? 'App\\Models\\Employee' : 'App\\Models\\User';
            }
            AuditLog::create($payload);
        }
    }

    private function getLeaveReason($type)
    {
        $reasons = [
            'annual' => 'Annual vacation with family',
            'sick' => 'Feeling unwell, need rest',
            'emergency' => 'Family emergency',
            'unpaid' => 'Personal matters to attend to',
            'maternity' => 'Maternity leave',
            'paternity' => 'Paternity leave',
        ];

        return $reasons[$type] ?? 'Leave request';
    }

    private function getModelTypeForAction($action)
    {
        $models = [
            'login' => null,
            'logout' => null,
            'create' => 'App\\Models\\Order',
            'update' => 'App\\Models\\Product',
            'delete' => 'App\\Models\\User',
            'view' => 'App\\Models\\Order',
            'password_change' => 'App\\Models\\User',
            'profile_update' => 'App\\Models\\Employee',
            'order_placed' => 'App\\Models\\Order',
            'payment_processed' => 'App\\Models\\EnhancedFinancialTransaction',
        ];

        return $models[$action] ?? null;
    }

    private function generateRandomIP()
    {
        return rand(1, 255).'.'.rand(1, 255).'.'.rand(1, 255).'.'.rand(1, 255);
    }
}
