<?php

namespace App\Http\Requests\TodoModel;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UniqueTitleForUserOnStoreRule;

class CreateRequest extends FormRequest
{
    const PRIORITY_LEVELS = ['low', 'medium', 'high'];

    /**
     * Determine if the user is authorized to make this request.
     */
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required','string','max:255', new UniqueTitleForUserOnStoreRule],
            'description' => ['required','string'],
            'priority' => ['required', 'string', 'in:' . implode(',', self::PRIORITY_LEVELS)],
            'due_date' => ['required','date', 'date_format:Y-m-d', 'after_or_equal:time_started'],
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
            'title.required' => 'The title field is required.',
            'due_date.after_or_equal' => 'The due date must be after or equal to the start time.',
            'priority.in' => "The selected priority is invalid. selection must be: [" . implode(', ', self::PRIORITY_LEVELS) . "]"
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
