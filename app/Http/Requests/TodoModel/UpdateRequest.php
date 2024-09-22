<?php

namespace App\Http\Requests\TodoModel;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UniqueTitleForUserOnUpdateRule;
use App\Rules\EnsureTasksIDAlignToItsUserRule;

class UpdateRequest extends FormRequest
{
    const PRIORITY_LEVELS = ['low', 'medium', 'high'];

    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'time_started' => now(),
        ]);
    }

    public function rules(): array
    {
        return [
            'update_title' => ['required','string','max:255', new UniqueTitleForUserOnUpdateRule($this->task_id)],
            'update_description' => ['required','string'],
            'update_priority' => ['required', 'string', 'in:' . implode(',', self::PRIORITY_LEVELS)],
            'update_due_date' => ['required','date', 'date_format:Y-m-d', 'after_or_equal:time_started'],
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
            'update_title.required' => 'The title field is required.',
            'due_date.after_or_equal' => 'The due date must be after or equal to the start time.',
            'update_priority.in' => "The selected priority is invalid. selection must be: [" . implode(', ', self::PRIORITY_LEVELS) . "]"
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
