<?php

namespace Jonasgn\LaravelCommons\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Jonasgn\LaravelCommons\Formatter;

class Cep implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $length = strlen(Formatter::toOnlyNumbers($value));

        if ($length !== 8) {
            $fail('O campo :attribute não é um CEP válido');
        }
    }
}
