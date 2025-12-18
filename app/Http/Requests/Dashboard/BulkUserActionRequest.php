<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request for bulk user actions
 * 
 * @see Requirements 7.5, 15.2
 */
class BulkUserActionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'action' => 'required|string|in:activate,deactivate,verify,delete',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'integer|exists:users,id',
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
            'action.required' => __('Please select an action to perform.'),
            'action.in' => __('The selected action is invalid.'),
            'user_ids.required' => __('Please select at least one user.'),
            'user_ids.min' => __('Please select at least one user.'),
            'user_ids.*.exists' => __('One or more selected users do not exist.'),
        ];
    }
}
