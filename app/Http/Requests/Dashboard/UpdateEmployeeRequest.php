<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request for updating employees
 * 
 * @see Requirements 10.1, 15.2
 */
class UpdateEmployeeRequest extends FormRequest
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
        $employeeId = $this->route('employeeId');
        
        return [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:employees,email,' . $employeeId,
            'phone' => 'sometimes|string|max:20',
            'department' => 'sometimes|string|max:255',
            'position' => 'sometimes|string|max:255',
            'salary' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|string|in:active,on_leave,suspended,terminated',
            'employment_type' => 'sometimes|string|in:full-time,part-time,contract',
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
            'first_name.max' => __('First name cannot exceed 255 characters.'),
            'last_name.max' => __('Last name cannot exceed 255 characters.'),
            'email.email' => __('Please enter a valid email address.'),
            'email.unique' => __('This email address is already in use.'),
            'phone.max' => __('Phone number cannot exceed 20 characters.'),
            'salary.numeric' => __('Salary must be a number.'),
            'salary.min' => __('Salary cannot be negative.'),
            'status.in' => __('Invalid status selected.'),
            'employment_type.in' => __('Invalid employment type selected.'),
        ];
    }
}
