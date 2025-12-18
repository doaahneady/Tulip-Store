<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request for creating employees
 * 
 * @see Requirements 10.1, 15.2
 */
class StoreEmployeeRequest extends FormRequest
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
            'employee_code' => 'required|string|unique:employees,employee_code',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'required|string|max:20',
            'department' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'employment_type' => 'sometimes|string|in:full-time,part-time,contract',
            'national_id' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
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
            'employee_code.required' => __('Employee code is required.'),
            'employee_code.unique' => __('This employee code is already in use.'),
            'first_name.required' => __('First name is required.'),
            'last_name.required' => __('Last name is required.'),
            'email.required' => __('Email address is required.'),
            'email.email' => __('Please enter a valid email address.'),
            'email.unique' => __('This email address is already in use.'),
            'phone.required' => __('Phone number is required.'),
            'department.required' => __('Department is required.'),
            'position.required' => __('Position is required.'),
            'hire_date.required' => __('Hire date is required.'),
            'hire_date.date' => __('Please enter a valid date.'),
            'salary.required' => __('Salary is required.'),
            'salary.numeric' => __('Salary must be a number.'),
            'salary.min' => __('Salary cannot be negative.'),
            'employment_type.in' => __('Invalid employment type selected.'),
            'gender.in' => __('Invalid gender selected.'),
        ];
    }
}
