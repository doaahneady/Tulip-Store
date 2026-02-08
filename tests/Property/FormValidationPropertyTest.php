<?php

namespace Tests\Property;

use App\Http\Requests\Dashboard\BulkUserActionRequest;
use App\Http\Requests\Dashboard\CalculatePayrollRequest;
use App\Http\Requests\Dashboard\RecordAttendanceRequest;
use App\Http\Requests\Dashboard\StoreEmployeeRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

/**
 * Property-Based Tests for Form Validation Error Display
 *
 * These tests verify that form validation correctly identifies invalid inputs
 * and returns appropriate error messages for each invalid field.
 *
 * **Feature: dashboard-system-rebuild, Property 22: Form Validation Error Display**
 * **Validates: Requirements 15.2**
 */
class FormValidationPropertyTest extends TestCase
{
    /**
     * Generate random invalid data for BulkUserActionRequest
     */
    protected function generateInvalidBulkUserData(): array
    {
        $invalidCases = [
            // Missing action
            ['user_ids' => [1, 2, 3]],
            // Invalid action
            ['action' => 'invalid_action', 'user_ids' => [1, 2, 3]],
            // Missing user_ids
            ['action' => 'activate'],
            // Empty user_ids array
            ['action' => 'activate', 'user_ids' => []],
            // Non-array user_ids
            ['action' => 'activate', 'user_ids' => 'not_an_array'],
            // Non-integer user_ids
            ['action' => 'activate', 'user_ids' => ['a', 'b', 'c']],
        ];

        return $invalidCases[array_rand($invalidCases)];
    }

    /**
     * Generate random invalid data for StoreEmployeeRequest
     */
    protected function generateInvalidEmployeeData(): array
    {
        $invalidCases = [
            // Missing required fields
            [],
            // Missing employee_code
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'phone' => '1234567890',
                'department' => 'IT',
                'position' => 'Developer',
                'hire_date' => '2024-01-01',
                'salary' => 50000,
            ],
            // Invalid email format
            [
                'employee_code' => 'EMP001',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'invalid-email',
                'phone' => '1234567890',
                'department' => 'IT',
                'position' => 'Developer',
                'hire_date' => '2024-01-01',
                'salary' => 50000,
            ],
            // Negative salary
            [
                'employee_code' => 'EMP001',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'phone' => '1234567890',
                'department' => 'IT',
                'position' => 'Developer',
                'hire_date' => '2024-01-01',
                'salary' => -1000,
            ],
            // Invalid hire_date format
            [
                'employee_code' => 'EMP001',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'phone' => '1234567890',
                'department' => 'IT',
                'position' => 'Developer',
                'hire_date' => 'not-a-date',
                'salary' => 50000,
            ],
            // Invalid employment_type
            [
                'employee_code' => 'EMP001',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'phone' => '1234567890',
                'department' => 'IT',
                'position' => 'Developer',
                'hire_date' => '2024-01-01',
                'salary' => 50000,
                'employment_type' => 'invalid-type',
            ],
            // Invalid gender
            [
                'employee_code' => 'EMP001',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'phone' => '1234567890',
                'department' => 'IT',
                'position' => 'Developer',
                'hire_date' => '2024-01-01',
                'salary' => 50000,
                'gender' => 'invalid-gender',
            ],
        ];

        return $invalidCases[array_rand($invalidCases)];
    }

    /**
     * Generate random invalid data for RecordAttendanceRequest
     */
    protected function generateInvalidAttendanceData(): array
    {
        $invalidCases = [
            // Missing employee_id
            ['date' => '2024-01-01', 'status' => 'present'],
            // Missing date
            ['employee_id' => 1, 'status' => 'present'],
            // Missing status
            ['employee_id' => 1, 'date' => '2024-01-01'],
            // Invalid status
            ['employee_id' => 1, 'date' => '2024-01-01', 'status' => 'invalid_status'],
            // Invalid date format
            ['employee_id' => 1, 'date' => 'not-a-date', 'status' => 'present'],
            // Invalid check_in format
            ['employee_id' => 1, 'date' => '2024-01-01', 'status' => 'present', 'check_in' => 'invalid'],
            // Invalid check_out format
            ['employee_id' => 1, 'date' => '2024-01-01', 'status' => 'present', 'check_out' => 'invalid'],
            // Notes too long
            ['employee_id' => 1, 'date' => '2024-01-01', 'status' => 'present', 'notes' => str_repeat('a', 501)],
        ];

        return $invalidCases[array_rand($invalidCases)];
    }

    /**
     * Generate random invalid data for CalculatePayrollRequest
     */
    protected function generateInvalidPayrollData(): array
    {
        $invalidCases = [
            // Missing employee_id
            ['month' => '2024-01'],
            // Missing month
            ['employee_id' => 1],
            // Invalid month format
            ['employee_id' => 1, 'month' => '2024-1'],
            // Invalid month format (full date)
            ['employee_id' => 1, 'month' => '2024-01-01'],
            // Negative allowances
            ['employee_id' => 1, 'month' => '2024-01', 'allowances' => -100],
            // Negative bonuses
            ['employee_id' => 1, 'month' => '2024-01', 'bonuses' => -100],
            // Negative deductions
            ['employee_id' => 1, 'month' => '2024-01', 'deductions' => -100],
            // Negative tax
            ['employee_id' => 1, 'month' => '2024-01', 'tax' => -100],
            // Negative insurance
            ['employee_id' => 1, 'month' => '2024-01', 'insurance' => -100],
            // Non-numeric allowances
            ['employee_id' => 1, 'month' => '2024-01', 'allowances' => 'not-a-number'],
        ];

        return $invalidCases[array_rand($invalidCases)];
    }

    /**
     * Get the expected invalid fields for a given invalid data set
     */
    protected function getExpectedInvalidFields(array $data, array $rules): array
    {
        $validator = Validator::make($data, $rules);

        return array_keys($validator->errors()->toArray());
    }

    /**
     * Remove database-dependent rules (exists, unique) for unit testing
     * This allows testing validation logic without database dependencies
     */
    protected function removeDbRules(array $rules): array
    {
        $cleanRules = [];
        foreach ($rules as $field => $rule) {
            if (is_string($rule)) {
                // Remove exists and unique rules from string rules
                $parts = explode('|', $rule);
                $parts = array_filter($parts, fn ($p) => ! str_starts_with($p, 'exists:') &&
                    ! str_starts_with($p, 'unique:')
                );
                $cleanRules[$field] = implode('|', $parts);
            } elseif (is_array($rule)) {
                // Remove exists and unique rules from array rules
                $cleanRules[$field] = array_filter($rule, fn ($r) => ! is_string($r) ||
                    (! str_starts_with($r, 'exists:') && ! str_starts_with($r, 'unique:'))
                );
            } else {
                $cleanRules[$field] = $rule;
            }
        }

        return $cleanRules;
    }

    /**
     * **Feature: dashboard-system-rebuild, Property 22: Form Validation Error Display**
     * **Validates: Requirements 15.2**
     *
     * *For any* form submission with invalid input, the response SHALL contain
     * error messages for each invalid field.
     *
     * @test
     */
    public function property_form_validation_returns_errors_for_invalid_fields(): void
    {
        // Run 100 iterations with random invalid data
        for ($i = 0; $i < 100; $i++) {
            // Randomly select a form request type
            $formType = rand(0, 3);

            switch ($formType) {
                case 0:
                    $invalidData = $this->generateInvalidBulkUserData();
                    $rules = $this->removeDbRules((new BulkUserActionRequest)->rules());
                    $formName = 'BulkUserAction';
                    break;
                case 1:
                    $invalidData = $this->generateInvalidEmployeeData();
                    $rules = $this->removeDbRules((new StoreEmployeeRequest)->rules());
                    $formName = 'StoreEmployee';
                    break;
                case 2:
                    $invalidData = $this->generateInvalidAttendanceData();
                    $rules = $this->removeDbRules((new RecordAttendanceRequest)->rules());
                    $formName = 'RecordAttendance';
                    break;
                case 3:
                    $invalidData = $this->generateInvalidPayrollData();
                    $rules = $this->removeDbRules((new CalculatePayrollRequest)->rules());
                    $formName = 'CalculatePayroll';
                    break;
            }

            // Create validator with the invalid data
            $validator = Validator::make($invalidData, $rules);

            // Assert: Validation should fail
            $this->assertTrue(
                $validator->fails(),
                "Iteration $i ($formName): Validation should fail for invalid data: ".
                json_encode($invalidData)
            );

            // Assert: There should be at least one error message
            $errors = $validator->errors();
            $this->assertGreaterThan(
                0,
                $errors->count(),
                "Iteration $i ($formName): Should have at least one error message"
            );

            // Assert: Each error should have a non-empty message
            foreach ($errors->all() as $message) {
                $this->assertNotEmpty(
                    $message,
                    "Iteration $i ($formName): Error message should not be empty"
                );
                $this->assertIsString(
                    $message,
                    "Iteration $i ($formName): Error message should be a string"
                );
            }
        }
    }

    /**
     * Property test: Valid data should pass validation
     *
     * @test
     */
    public function property_valid_data_passes_validation(): void
    {
        // Run 100 iterations with valid data
        for ($i = 0; $i < 100; $i++) {
            // Generate valid BulkUserAction data
            $validBulkUserData = [
                'action' => ['activate', 'deactivate', 'verify', 'delete'][rand(0, 3)],
                'user_ids' => [rand(1, 1000), rand(1, 1000)],
            ];

            $rules = (new BulkUserActionRequest)->rules();
            // Remove the exists rule for this test since we don't have a database
            $rules['user_ids.*'] = 'integer';

            $validator = Validator::make($validBulkUserData, $rules);

            $this->assertFalse(
                $validator->fails(),
                "Iteration $i: Valid BulkUserAction data should pass validation. Errors: ".
                json_encode($validator->errors()->toArray())
            );
        }
    }

    /**
     * Property test: Custom error messages are returned
     *
     * @test
     */
    public function property_custom_error_messages_are_returned(): void
    {
        // Run 100 iterations
        for ($i = 0; $i < 100; $i++) {
            // Test BulkUserActionRequest with missing action
            $invalidData = ['user_ids' => [1, 2, 3]];
            $request = new BulkUserActionRequest;
            $rules = $this->removeDbRules($request->rules());
            $messages = $request->messages();

            $validator = Validator::make($invalidData, $rules, $messages);

            $this->assertTrue($validator->fails());

            // Check that the custom message is used
            $actionErrors = $validator->errors()->get('action');
            $this->assertNotEmpty($actionErrors);

            // The error message should be the custom one
            $this->assertStringContainsString(
                'select an action',
                strtolower($actionErrors[0]),
                "Iteration $i: Should use custom error message for action field"
            );
        }
    }

    /**
     * Property test: Each invalid field has exactly one primary error
     *
     * @test
     */
    public function property_each_invalid_field_has_error_message(): void
    {
        // Run 100 iterations
        for ($i = 0; $i < 100; $i++) {
            // Create data with multiple invalid fields
            $invalidData = [
                'employee_code' => '', // Required but empty
                'first_name' => '', // Required but empty
                'email' => 'invalid-email', // Invalid format
                'salary' => -100, // Negative value
            ];

            $rules = $this->removeDbRules((new StoreEmployeeRequest)->rules());
            $validator = Validator::make($invalidData, $rules);

            $this->assertTrue($validator->fails());

            $errors = $validator->errors();

            // Each invalid field should have at least one error
            $invalidFields = ['employee_code', 'first_name', 'email', 'salary'];
            foreach ($invalidFields as $field) {
                $fieldErrors = $errors->get($field);
                $this->assertNotEmpty(
                    $fieldErrors,
                    "Iteration $i: Field '$field' should have an error message"
                );
            }
        }
    }
}
