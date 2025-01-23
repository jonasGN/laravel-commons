<?php

namespace Jonasgn\LaravelCommons\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Aplica uma regra apenas quando sua condição for verdadeira
 */
class ApplyWhen implements ValidationRule
{
    private function __construct(private ValidationRule $rule, private bool $condition) {}

    public static function create(ValidationRule $rule, bool $condition)
    {
        return new static($rule, $condition);
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->condition) return;

        $this->rule->validate($attribute, $value, $fail);
    }
}
