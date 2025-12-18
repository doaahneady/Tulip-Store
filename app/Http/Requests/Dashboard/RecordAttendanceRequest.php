<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request for recording attendance
 * 
 * @see Requirements 10.2, 15.2
 */
class RecordAttendanceRequest extends FormRequest
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
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'status' => 'required|string|in:present,absent,late,half_day,on_leave',
            'notes' => 'nullable|string|max:500',
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
            'date.required' => __('Date is required.'),
            'date.date' => __('Please enter a valid date.'),
            'check_in.date_format' => __('Check-in time must be in HH:MM format.'),
            'check_out.date_format' => __('Check-out time must be in HH:MM format.'),
            'status.required' => __('Attendance status is required.'),
            'status.in' => __('Invalid attendance status selected.'),
            'notes.max' => __('Notes cannot exceed 500 characters.'),
        ];
    }
}
