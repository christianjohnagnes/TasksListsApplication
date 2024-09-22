<?php

namespace App\Rules;

use Closure;
use App\Models\TodoItem;
use Illuminate\Contracts\Validation\ValidationRule;

class EnsureStatusCanBeUpdatedRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Retrieve the task ID from the request
        $taskId = request()->input('task_id');
        $todoItem = TodoItem::find($taskId);
        
        // Check if the todo item exists and its current status
        if ($todoItem && $todoItem->status !== 'PD') {
            $fail('The status can only be updated if it is currently pending (PD).');
        }
    }
}
