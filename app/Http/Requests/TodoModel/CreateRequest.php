<?php

namespace App\Http\Requests\TodoModel;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UniqueTitleForUserOnStoreRule;

class CreateRequest extends FormRequest
{
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
            'title' => ['required','string','max:255', new UniqueTitleForUserOnStoreRule],
            'description' => ['required','string'],
            'priority' => ['required', 'string'],
            'due_date' => ['required','date','after_or_equal:time_started'],
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
        ];
    }
}
