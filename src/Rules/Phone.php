<?php

namespace Jonasgn\LaravelCommons\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Jonasgn\LaravelCommons\Formatter;

class Phone implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // verifica se o número de telefone está em um dos formatos: `(00) 98888-7777` ou `(00) 8888-9999`
        $hasPhonePattern = preg_match('/^\(\d{2}\)\s?\d{4,5}-\d{4}$/', $value) > 0;

        // se o pattern estiver correto, significa que pode ser um número de telefone válido
        if ($hasPhonePattern) return;

        $failMessage = 'O campo :attribute, não é um número de telefone com DDD válido';

        $hasOnlyNumbers = Formatter::hasOnlyNumbers($value);
        if (!$hasOnlyNumbers) {
            $fail($failMessage);
            return;
        }

        $phoneLenght = strlen(Formatter::toOnlyNumbers($value));
        $hasPhoneLength = $phoneLenght === 10 || $phoneLenght === 11;
        if (!$hasPhoneLength) {
            $fail($failMessage);
        }
    }
}
