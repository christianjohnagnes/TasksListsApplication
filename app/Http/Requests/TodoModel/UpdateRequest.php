<?php

namespace App\Http\Requests\TodoModel;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UniqueTitleForUserOnUpdateRule;
use App\Rules\EnsureTasksIDAlignToItsUserRule;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'task_id' => ['required', new EnsureTasksIDAlignToItsUserRule],
            'update_title' => ['required','string','max:255', new UniqueTitleForUserOnUpdateRule($this->task_id)],
            'update_description' => ['required','string'],
            'update_priority' => ['required', 'string'],
            'update_due_date' => ['required','date','after_or_equal:time_started']
        ];
    }

    public function messages(): array
    {
        return [
            'update_title.required' => 'The title field is required.',
            'update_due_date.after_or_equal' => 'The due date must be after or equal to the start time.',
        ];
    }
}
