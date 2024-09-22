<?php

namespace App\Http\Requests\Api;

use App\Rules\EnsureStatusCanBeUpdatedRule;
use Illuminate\Foundation\Http\FormRequest;

class TasksStatusRequest extends FormRequest
{

    CONST STATUS_LEVEL = ['completed', 'incomplete'];
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'task_id' => ['required', 'exists:todo_items,id'],
            'status' => ['required', 'string', 'in:' . implode(',', self::STATUS_LEVEL),  new EnsureStatusCanBeUpdatedRule],
            'time_ended' => now()
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status.in' => "The selected status is invalid. selection must be: [" . implode(', ', self::STATUS_LEVEL) . "]"
        ];
    }

    /**
     * Handle failed validation.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException($validator, response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }
}
