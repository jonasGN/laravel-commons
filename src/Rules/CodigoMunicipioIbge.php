<?php

namespace Jonasgn\LaravelCommons\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Valida se o código municipal do IBGE informado é um código válido
 */
class CodigoMunicipioIbge implements ValidationRule
{
    const UF = [
        11 => 'RO',
        12 => 'AC',
        13 => 'AM',
        14 => 'RR',
        15 => 'PA',
        16 => 'AP',
        17 => 'TO',
        21 => 'MA',
        22 => 'PI',
        23 => 'CE',
        24 => 'RN',
        25 => 'PB',
        26 => 'PE',
        27 => 'AL',
        28 => 'SE',
        29 => 'BA',
        31 => 'MG',
        32 => 'ES',
        33 => 'RJ',
        35 => 'SP',
        41 => 'PR',
        42 => 'SC',
        43 => 'RS',
        50 => 'MS',
        51 => 'MT',
        52 => 'GO',
        53 => 'DF',
    ];

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $lenght = strlen($value);
        $defaultFailMsg = 'O campo :attribute, não é um código de munípio do IBGE válido';

        // o código do IBGE para os munícipios, devem conter 7 dígitos
        if ($lenght !== 7 || !is_numeric($value)) {
            $fail($defaultFailMsg);
            return;
        }

        // valida se o código UF informado corresponde aos códigos nacionais
        $ufCode = (int) substr($value, 0, 2);

        if (!isset(static::UF[$ufCode])) {
            $fail($defaultFailMsg);
        }
    }
}
