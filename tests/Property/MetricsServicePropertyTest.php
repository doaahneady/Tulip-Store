<?php

namespace Tests\Property;

use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Services\Dashboard\MetricsService;
use Mockery;
use Tests\TestCase;

/**
 * Property-Based Tests for MetricsService
 *
 * These tests verify the correctness properties of the MetricsService
 * by running multiple iterations with randomly generated test data.
 */
class MetricsServicePropertyTest extends TestCase
{
    protected MetricsService $metricsService;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a mock OrderRepository since we're testing formatting methods
        $mockOrderRepository = Mockery::mock(OrderRepositoryInterface::class);
        $this->metricsService = new MetricsService($mockOrderRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Available currencies for testing
     */
    protected array $currencies = ['USD', 'EUR', 'GBP', 'JPY', 'CNY', 'INR', 'AED', 'SAR', 'EGP', 'IQD'];

    /**
     * Available locales for testing
     */
    protected array $locales = ['en_US', 'en_GB', 'de_DE', 'fr_FR', 'ar_SA', 'ar_AE', 'ar_EG'];

    /**
     * Currency symbols mapping
     */
    protected array $currencySymbols = [
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

    /**
     * Generate a random monetary amount
     */
    protected function generateRandomAmount(): float
    {
        // Generate amounts from -999999.99 to 999999.99
        $sign = rand(0, 1) === 0 ? 1 : -1;

        return $sign * round(rand(0, 99999999) / 100, 2);
    }

    /**
     * Generate a random percentage value
     */
    protected function generateRandomPercentage(): float
    {
        // Generate percentages from -1000% to 1000%
        $sign = rand(0, 1) === 0 ? 1 : -1;

        return $sign * round(rand(0, 100000) / 100, 2);
    }

    /**
     * Check if a string contains thousand separators
     */
    protected function hasThousandSeparators(string $formatted, float $amount, string $locale): bool
    {
        $absAmount = abs($amount);

        // Only amounts >= 1000 should have thousand separators
        if ($absAmount < 1000) {
            return true; // No separators needed
        }

        // Get the locale-specific thousand separator
        $separators = [
            'en_US' => ',',
            'en_GB' => ',',
            'de_DE' => '.',
            'fr_FR' => ' ',
            'ar_SA' => ',',
            'ar_AE' => ',',
            'ar_EG' => ',',
        ];

        $separator = $separators[$locale] ?? ',';

        // The formatted string should contain the thousand separator
        return str_contains($formatted, $separator);
    }

    /**
     * **Feature: dashboard-system-rebuild, Property 5: Currency Formatting Consistency**
     * **Validates: Requirements 3.4**
     *
     * *For any* monetary value, the formatted output SHALL contain the currency
     * symbol and use thousand separators according to locale rules.
     *
     * @test
     */
    public function property_currency_formatting_contains_symbol_and_separators(): void
    {
        // Run 100 iterations with random data
        for ($i = 0; $i < 100; $i++) {
            $amount = $this->generateRandomAmount();
            $currency = $this->currencies[array_rand($this->currencies)];
            $locale = $this->locales[array_rand($this->locales)];

            $formatted = $this->metricsService->formatCurrency($amount, $currency, $locale);

            // Property 1: The formatted string must contain the currency symbol
            $symbol = $this->currencySymbols[$currency] ?? $currency;
            $this->assertStringContainsString(
                $symbol,
                $formatted,
                "Iteration $i: Formatted currency '$formatted' should contain symbol '$symbol' for $currency"
            );

            // Property 2: For amounts >= 1000, the formatted string must contain thousand separators
            $this->assertTrue(
                $this->hasThousandSeparators($formatted, $amount, $locale),
                "Iteration $i: Formatted currency '$formatted' should have thousand separators for amount $amount in locale $locale"
            );

            // Property 3: Negative amounts should have a minus sign
            if ($amount < 0) {
                $this->assertStringContainsString(
                    '-',
                    $formatted,
                    "Iteration $i: Formatted currency '$formatted' should contain minus sign for negative amount $amount"
                );
            }
        }
    }

    /**
     * Additional test: Verify currency formatting with specific edge cases
     *
     * @test
     */
    public function property_currency_formatting_handles_edge_cases(): void
    {
        // Test zero
        $formatted = $this->metricsService->formatCurrency(0.0, 'USD', 'en_US');
        $this->assertStringContainsString('$', $formatted);
        $this->assertStringContainsString('0', $formatted);

        // Test very large numbers
        $formatted = $this->metricsService->formatCurrency(1234567.89, 'USD', 'en_US');
        $this->assertStringContainsString('$', $formatted);
        $this->assertStringContainsString(',', $formatted); // Thousand separator

        // Test JPY (no decimals)
        $formatted = $this->metricsService->formatCurrency(1234.56, 'JPY', 'en_US');
        $this->assertStringContainsString('¥', $formatted);
        $this->assertStringNotContainsString('.', $formatted); // No decimal point for JPY
    }

    /**
     * **Feature: dashboard-system-rebuild, Property 6: Percentage Change Color Coding**
     * **Validates: Requirements 3.5**
     *
     * *For any* percentage value, positive values SHALL be displayed with green
     * color class and negative values SHALL be displayed with red color class.
     *
     * @test
     */
    public function property_percentage_color_coding_matches_sign(): void
    {
        // Run 100 iterations with random data
        for ($i = 0; $i < 100; $i++) {
            $percentage = $this->generateRandomPercentage();

            $result = $this->metricsService->formatPercentage($percentage);

            // Verify result structure
            $this->assertArrayHasKey('value', $result, "Iteration $i: Result should have 'value' key");
            $this->assertArrayHasKey('color', $result, "Iteration $i: Result should have 'color' key");
            $this->assertArrayHasKey('icon', $result, "Iteration $i: Result should have 'icon' key");

            // Property: Color coding must match the sign of the percentage
            if ($percentage > 0) {
                $this->assertEquals(
                    'green',
                    $result['color'],
                    "Iteration $i: Positive percentage $percentage should have green color, got '{$result['color']}'"
                );
                $this->assertEquals(
                    'arrow-up',
                    $result['icon'],
                    "Iteration $i: Positive percentage $percentage should have arrow-up icon, got '{$result['icon']}'"
                );
                $this->assertStringStartsWith(
                    '+',
                    $result['value'],
                    "Iteration $i: Positive percentage value should start with '+'"
                );
            } elseif ($percentage < 0) {
                $this->assertEquals(
                    'red',
                    $result['color'],
                    "Iteration $i: Negative percentage $percentage should have red color, got '{$result['color']}'"
                );
                $this->assertEquals(
                    'arrow-down',
                    $result['icon'],
                    "Iteration $i: Negative percentage $percentage should have arrow-down icon, got '{$result['icon']}'"
                );
                $this->assertStringStartsWith(
                    '-',
                    $result['value'],
                    "Iteration $i: Negative percentage value should start with '-'"
                );
            } else {
                // Zero case
                $this->assertEquals(
                    'gray',
                    $result['color'],
                    "Iteration $i: Zero percentage should have gray color, got '{$result['color']}'"
                );
                $this->assertEquals(
                    'minus',
                    $result['icon'],
                    "Iteration $i: Zero percentage should have minus icon, got '{$result['icon']}'"
                );
            }

            // Property: Value should contain percentage sign
            $this->assertStringContainsString(
                '%',
                $result['value'],
                "Iteration $i: Formatted percentage '{$result['value']}' should contain '%' symbol"
            );
        }
    }

    /**
     * Additional test: Verify percentage formatting with specific values
     *
     * @test
     */
    public function property_percentage_formatting_specific_values(): void
    {
        // Test positive percentage
        $result = $this->metricsService->formatPercentage(25.5);
        $this->assertEquals('green', $result['color']);
        $this->assertEquals('arrow-up', $result['icon']);
        $this->assertEquals('+25.5%', $result['value']);

        // Test negative percentage
        $result = $this->metricsService->formatPercentage(-15.3);
        $this->assertEquals('red', $result['color']);
        $this->assertEquals('arrow-down', $result['icon']);
        $this->assertEquals('-15.3%', $result['value']);

        // Test zero
        $result = $this->metricsService->formatPercentage(0.0);
        $this->assertEquals('gray', $result['color']);
        $this->assertEquals('minus', $result['icon']);
        $this->assertEquals('0.0%', $result['value']);
    }
}
