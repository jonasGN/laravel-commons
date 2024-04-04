<?php

namespace Jonasgn\LaravelCommons;

use InvalidArgumentException;
use Normalizer;

/**
 * Classe auxiliar, responsável por transformar valores
 */
abstract class Formatter
{
    /**
     * Limpa o valor informado para apenas valores númericos
     */
    public static function toOnlyNumbers(?string $value): string
    {
        if ($value === null) return '';
        if (empty(trim($value))) return '';

        $result = preg_replace('/[^0-9]/', '', trim($value));
        return $result;
    }

    /**
     * Transforma uma string em número de telefone formatado nos seguintes padrões:
     * `00988887777` para `(00) 98888-7777` e `0088887777` para `(00) 8888-7777`
     */
    public static function toPhoneNumber(string $value): string
    {
        $phone = static::toOnlyNumbers($value); // limpa antes de tentar formatar
        $length = strlen($phone);

        $areaCode = substr($phone, 0, 2);
        $first = "";
        $second = "";

        if ($length == 11) {
            // formata a string no formato `00988887777` para `(00) 98888-7777`
            $first = substr($phone, 2, 5);
            $second = substr($phone, 7, 4);
        } else if ($length == 10) {
            // formata a string no formato `0088887777` para `(00) 8888-7777`
            $first = substr($phone, 2, 4);
            $second = substr($phone, 6, 4);
        } else {
            throw new InvalidArgumentException("O valor '$value', não pode ser transformado em número de telefone");
        }

        return "($areaCode) $first-$second";
    }

    public static function toCep(string $rawCep): string
    {
        $rawCep = static::toOnlyNumbers($rawCep);
        if (strlen($rawCep) !== 8) {
            throw new InvalidArgumentException("O valor '$rawCep', não pode ser transformado em um CEP válido");
        }

        return preg_replace('/^(\d{5})(\d{3})$/', '$1-$2', $rawCep);
    }

    public static function toCpfCnpj(string $cpfcnpj): string
    {
        $cpfcnpj = Formatter::toOnlyNumbers($cpfcnpj);

        if (strlen($cpfcnpj) == 11) {
            $cpf = preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpfcnpj);
            return $cpf;
        }
        if (strlen($cpfcnpj) == 14) {
            $cnpj = preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cpfcnpj);
            return $cnpj;
        }

        throw new InvalidArgumentException('Formato do CPF ou CNPJ informado incorreto');
    }

    /**
     * Retorna o texto informado em sua versão sem caracterés látinos e em caixa alta
     * para comparação entre textos
     */
    public static function normalizeTextToCompare(string $text): string
    {
        $text = trim($text);
        $value = preg_replace('/[^a-zA-Z0-9\s]/u', '', Normalizer::normalize($text, Normalizer::FORM_KD));

        return strtoupper($value);
    }

    /**
     * Converte um número para uma string com casas decimais, por exemplo: 1 -> 01 e 10 -> 10.
     */
    public static function toDecimalString(string $value): string
    {
        return str_pad($value, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Compara duas strings para saber se as mesmas contém exatamente os mesmos caracteres
     */
    public static function isSameString(string $a, string $b): bool
    {
        $target = static::normalizeTextToCompare($a);
        $compare = static::normalizeTextToCompare($b);

        return $target === $compare;
    }

    public static function hasOnlyNumbers(string $value): bool
    {
        return ctype_digit($value);
    }

    /**
     * Retorna um número em sua versão monetária no padrão BRL
     */
    public static function toCurrency(int|float $value): string
    {
        return 'R$ ' . number_format($value, 2, ',', '.');
    }

    /**
     * Retorna a string normalizada com os primeiros caracteres em maísculo e os seguintes 
     * em mínusculo
     */
    public static function capitalizeWords(string $value): string
    {
        return ucwords(strtolower($value));
    }
}
