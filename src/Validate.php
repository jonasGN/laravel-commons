<?php

namespace Jonasgn\LaravelCommons;

/**
 * Classe auxiliar contendo métodos de validação
 */
abstract class Validate
{
    public static function isValidCpfCnpj(string $cpfcnpj): bool
    {
        $cpfcnpj = Formatter::toOnlyNumbers($cpfcnpj);
        $lenght = strlen($cpfcnpj);

        if (!($lenght == 11 || $lenght == 14))  return false;

        return $lenght == 11
            ? static::_validateCpf($cpfcnpj)
            : static::_validateCnpj($cpfcnpj);
    }

    public static function isValidCpf(string $cpf): bool
    {
        $cpf = Formatter::toOnlyNumbers($cpf);
        $lenght = strlen($cpf);

        return $lenght == 11
            ? static::_validateCpf($cpf)
            : false;
    }

    public static function isValidCnpj(string $cnpj): bool
    {
        $cnpj = Formatter::toOnlyNumbers($cnpj);
        $lenght = strlen($cnpj);

        return $lenght == 14
            ? static::_validateCnpj($cnpj)
            : false;
    }

    public static function isValidPisPasep(string $pispasep): bool
    {
        $pispasep = Formatter::toOnlyNumbers($pispasep);
        if (strlen($pispasep) !== 11) return false;

        // Calcula o dígito verificador
        $weight = [3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $sum = 0;

        for ($i = 0; $i < 10; $i++) {
            $sum += $pispasep[$i] * $weight[$i];
        }

        $resto = $sum % 11;
        $dv = $resto < 2 ? 0 : 11 - $resto;

        // Verifica se o dígito verificador está correto
        if ($dv == $pispasep[10]) {
            return true;
        } else {
            return false;
        }
    }

    private static function _validateCpf(string $cpf): bool
    {
        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) return false;

        // Faz o cálculo para validar o CPF
        for ($i = 9; $i < 11; $i++) {
            for ($j = 0, $c = 0; $c < $i; $c++) {
                $j += $cpf[$c] * (($i + 1) - $c);
            }

            $j = ((10 * $j) % 11) % 10;
            if ($cpf[$c] != $j) return false;
        }
        return true;
    }

    private static function _validateCnpj(string $cnpj)
    {
        // Verifica se todos os digitos são iguais
        if (preg_match('/(\d)\1{13}/', $cnpj)) return false;

        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) return false;

        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }
}
