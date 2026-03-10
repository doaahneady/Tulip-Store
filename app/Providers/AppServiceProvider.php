<?php

namespace App\Providers;

use App\Repositories\Contracts\AuditLogRepositoryInterface;
use App\Repositories\Contracts\FinancialTransactionRepositoryInterface;
// Repository Contracts
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\StoreRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\AuditLogRepository;
use App\Repositories\Eloquent\FinancialTransactionRepository;
// Repository Implementations
use App\Repositories\Eloquent\OrderRepository;
use App\Repositories\Eloquent\StoreRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Services\CurrencyService;
use App\Models\Order;
use App\Observers\OrderObserver;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind repository interfaces to their Eloquent implementations
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AuditLogRepositoryInterface::class, AuditLogRepository::class);
        $this->app->bind(StoreRepositoryInterface::class, StoreRepository::class);
        $this->app->bind(FinancialTransactionRepositoryInterface::class, FinancialTransactionRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register dashboard Blade components with 'dashboard.' prefix
        // This allows using <x-dashboard.alert>, <x-dashboard.form-errors>, etc.
        Blade::anonymousComponentPath(resource_path('views/dashboard/components'), 'dashboard');

        Order::observe(OrderObserver::class);

        Blade::directive('money', function ($expression) {
            return "<?php echo app(" . CurrencyService::class . "::class)->formatUsd((float)($expression)); ?>";
        });
    }
}
