<?php

namespace Jonasgn\LaravelCommons\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Jonasgn\LaravelCommons\Validate;

class CpfCnpj implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!Validate::isValidCpfCnpj($value)) {
            $fail('O campo :attribute deve conter um valor de CPF ou CNPJ válido');
        }
    }
}
