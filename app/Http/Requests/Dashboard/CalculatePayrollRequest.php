<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request for calculating payroll
 * 
 * @see Requirements 10.4, 15.2
 */
class CalculatePayrollRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && ($this->user()->hasRole('hr') || $this->user()->hasRole('admin'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employee_id' => 'required|integer|exists:employees,id',
            'month' => 'required|date_format:Y-m',
            'allowances' => 'nullable|numeric|min:0',
            'bonuses' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'insurance' => 'nullable|numeric|min:0',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'employee_id.required' => __('Please select an employee.'),
            'employee_id.exists' => __('The selected employee does not exist.'),
            'month.required' => __('Month is required.'),
            'month.date_format' => __('Month must be in YYYY-MM format.'),
            'allowances.numeric' => __('Allowances must be a number.'),
            'allowances.min' => __('Allowances cannot be negative.'),
            'bonuses.numeric' => __('Bonuses must be a number.'),
            'bonuses.min' => __('Bonuses cannot be negative.'),
            'deductions.numeric' => __('Deductions must be a number.'),
            'deductions.min' => __('Deductions cannot be negative.'),
            'tax.numeric' => __('Tax must be a number.'),
            'tax.min' => __('Tax cannot be negative.'),
            'insurance.numeric' => __('Insurance must be a number.'),
            'insurance.min' => __('Insurance cannot be negative.'),
        ];
    }
}
