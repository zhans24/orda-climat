<?php

namespace App\Rules;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class LeafCategory implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value) return;
        $hasChildren = Category::where('parent_id', $value)->exists();
        if ($hasChildren) {
            $fail('Товар можно привязать только к конечной (листовой) категории.');
        }
    }
}
