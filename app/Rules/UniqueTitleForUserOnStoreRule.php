<?php

namespace App\Rules;

use Closure;
use App\Models\TodoItem;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueTitleForUserOnStoreRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $todoItem = TodoItem::where('user_id', auth()->user()->id)
            ->where('title', $value)
            ->select('title')
            ->exists();

        if($todoItem) {
            $fail('The :attribute has already been taken.');
        }
    }
}
