<?php

namespace Jonasgn\LaravelCommons;

use InvalidArgumentException;

/**
 * Essa class mascara certos valores sensíveis para que dados sensíveis não sejam expostos publicamente
 */
class Mask
{
    public static function cpfcnpj(string $cpfcnpj): string
    {
        $cpfcnpj = Formatter::toOnlyNumbers($cpfcnpj);
        if (strlen($cpfcnpj) == 11) {
            return '***.***.' . substr($cpfcnpj, 6, 3) . '-' . substr($cpfcnpj, 9, 2);
        }
        if (strlen($cpfcnpj) == 14) {
            return '**.***.***/' . substr($cpfcnpj, 8, 4) . '-' . substr($cpfcnpj, 12, 2);
        }

        throw new InvalidArgumentException('Formato do CPF ou CNPJ informado incorreto');
    }

    public static function email(string $email): string
    {
        $email = trim($email);

        // divide o endereço de email a partir do caractere '@'
        list($user, $domain) = explode('@', $email);

        // ofusca o nome do usuário a partir do primeiro caractere
        $userOfuscated = substr($user, 0, 1) . str_repeat('*', strlen($user) - 2) . substr($user, -1);

        $emailOfuscated = $userOfuscated . '@' . $domain;
        return $emailOfuscated;
    }

    public static function phone(string $phone): string
    {
        $phoneNumber = Formatter::toPhoneNumber($phone);
        $phoneLength = strlen($phoneNumber);

        $result = '';
        if ($phoneLength == 14) {
            $result = substr($phoneNumber, 0, 6) . str_repeat('*', 3) . substr($phoneNumber, 9, 5);
        } else if ($phoneLength == 15) {
            $result = substr($phoneNumber, 0, 6) . str_repeat('*', 4) . substr($phoneNumber, 10, 5);
        }

        return $result;
    }
}
