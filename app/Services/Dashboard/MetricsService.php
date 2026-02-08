<?php

namespace App\Services\Dashboard;

use App\Repositories\Contracts\OrderRepositoryInterface;
use Carbon\Carbon;

class MetricsService
{
    public function __construct(
        protected OrderRepositoryInterface $orderRepository
    ) {}

    /**
     * Calculate total revenue within a date range
     *
     * @param  Carbon  $start  Start date
     * @param  Carbon  $end  End date
     * @param  int|null  $storeId  Optional store ID for filtering
     * @return float Total revenue
     */
    public function calculateRevenue(Carbon $start, Carbon $end, ?int $storeId = null): float
    {
        return $this->orderRepository->getTotalRevenue($start, $end, $storeId);
    }

    /**
     * Calculate order count within a date range
     *
     * @param  Carbon  $start  Start date
     * @param  Carbon  $end  End date
     * @param  int|null  $storeId  Optional store ID for filtering
     * @return int Order count
     */
    public function calculateOrderCount(Carbon $start, Carbon $end, ?int $storeId = null): int
    {
        return $this->orderRepository->getOrderCount($start, $end, $storeId);
    }

    /**
     * Calculate growth percentage between current and previous values
     *
     * @param  float  $current  Current period value
     * @param  float  $previous  Previous period value
     * @return float Growth percentage
     */
    public function calculateGrowthPercentage(float $current, float $previous): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100.0 : 0.0;
        }

        return (($current - $previous) / $previous) * 100;
    }

    /**
     * Format a monetary value with currency symbol and thousand separators
     *
     * @param  float  $amount  The amount to format
     * @param  string  $currency  Currency code (default: USD)
     * @param  string  $locale  Locale for formatting (default: en_US)
     * @return string Formatted currency string
     */
    public function formatCurrency(float $amount, string $currency = 'USD', string $locale = 'en_US'): string
    {
        // Currency symbols mapping
        $currencySymbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'JPY' => '¥',
            'CNY' => '¥',
            'INR' => '₹',
            'AED' => 'د.إ',
            'SAR' => 'ر.س',
            'EGP' => 'ج.م',
            'IQD' => 'ع.د',
        ];

        // Locale-specific formatting rules
        $localeSettings = [
            'en_US' => ['decimal' => '.', 'thousands' => ',', 'symbol_position' => 'before'],
            'en_GB' => ['decimal' => '.', 'thousands' => ',', 'symbol_position' => 'before'],
            'de_DE' => ['decimal' => ',', 'thousands' => '.', 'symbol_position' => 'after'],
            'fr_FR' => ['decimal' => ',', 'thousands' => ' ', 'symbol_position' => 'after'],
            'ar_SA' => ['decimal' => '.', 'thousands' => ',', 'symbol_position' => 'after'],
            'ar_AE' => ['decimal' => '.', 'thousands' => ',', 'symbol_position' => 'after'],
            'ar_EG' => ['decimal' => '.', 'thousands' => ',', 'symbol_position' => 'after'],
        ];

        $symbol = $currencySymbols[$currency] ?? $currency;
        $settings = $localeSettings[$locale] ?? $localeSettings['en_US'];

        // Determine decimal places (JPY has no decimals)
        $decimals = in_array($currency, ['JPY']) ? 0 : 2;

        // Format the number
        $formattedNumber = number_format(
            abs($amount),
            $decimals,
            $settings['decimal'],
            $settings['thousands']
        );

        // Handle negative amounts
        $prefix = $amount < 0 ? '-' : '';

        // Position the symbol
        if ($settings['symbol_position'] === 'before') {
            return $prefix.$symbol.$formattedNumber;
        } else {
            return $prefix.$formattedNumber.' '.$symbol;
        }
    }

    /**
     * Format a percentage value with color coding and icon
     *
     * @param  float  $value  The percentage value
     * @return array Array with 'value', 'color', and 'icon' keys
     */
    public function formatPercentage(float $value): array
    {
        $formattedValue = number_format(abs($value), 1).'%';

        if ($value > 0) {
            return [
                'value' => '+'.$formattedValue,
                'color' => 'green',
                'icon' => 'arrow-up',
            ];
        } elseif ($value < 0) {
            return [
                'value' => '-'.$formattedValue,
                'color' => 'red',
                'icon' => 'arrow-down',
            ];
        } else {
            return [
                'value' => $formattedValue,
                'color' => 'gray',
                'icon' => 'minus',
            ];
        }
    }

    /**
     * Get KPI metrics for a dashboard
     *
     * @param  Carbon  $currentStart  Start of current period
     * @param  Carbon  $currentEnd  End of current period
     * @param  Carbon  $previousStart  Start of previous period
     * @param  Carbon  $previousEnd  End of previous period
     * @param  int|null  $storeId  Optional store ID for filtering
     * @return array Array of KPI metrics
     */
    public function getKPIMetrics(
        Carbon $currentStart,
        Carbon $currentEnd,
        Carbon $previousStart,
        Carbon $previousEnd,
        ?int $storeId = null
    ): array {
        $currentRevenue = $this->calculateRevenue($currentStart, $currentEnd, $storeId);
        $previousRevenue = $this->calculateRevenue($previousStart, $previousEnd, $storeId);
        $revenueGrowth = $this->calculateGrowthPercentage($currentRevenue, $previousRevenue);

        $currentOrders = $this->calculateOrderCount($currentStart, $currentEnd, $storeId);
        $previousOrders = $this->calculateOrderCount($previousStart, $previousEnd, $storeId);
        $ordersGrowth = $this->calculateGrowthPercentage((float) $currentOrders, (float) $previousOrders);

        return [
            'revenue' => [
                'current' => $currentRevenue,
                'previous' => $previousRevenue,
                'formatted' => $this->formatCurrency($currentRevenue),
                'growth' => $this->formatPercentage($revenueGrowth),
            ],
            'orders' => [
                'current' => $currentOrders,
                'previous' => $previousOrders,
                'growth' => $this->formatPercentage($ordersGrowth),
            ],
        ];
    }
}
