<?php

use Jonasgn\LaravelCommons\Formatter;

describe('toOnlyNumbers tests', function () {
    it('should not be empty', function (string $value) {
        expect(Formatter::toOnlyNumbers($value))->not->toBeEmpty();
    })->with(['m9obLJQhezVLS2', 'P^J!3pRs7Kbwuj']);

    it('should be empty', function (string $value) {
        expect(Formatter::toOnlyNumbers($value))->toBeEmpty();
    })->with(['SyPcnfGHipXGBt', 'b^T@XpcrmW#xLT']);

    it('should be only numeric trimed chars', function () {
        expect(Formatter::toOnlyNumbers('ashuahs99888asa'))->toBe('99888');
        expect(Formatter::toOnlyNumbers(' ashuahs99888asa '))->toBe('99888');
    });

    it('should be empty string when given null or empty', function () {
        expect(Formatter::toOnlyNumbers(''))->toBe('');
        expect(Formatter::toOnlyNumbers(null))->toBe('');
    });
});

describe('toPhoneNumber tests', function () {
    it('should throw when phone number is invalid', function (string $value) {
        expect(fn () => Formatter::toPhoneNumber($value))->toThrow(
            InvalidArgumentException::class,
            "O valor '{$value}', não pode ser transformado em número de telefone"
        );
    })->with(['', '99999999', '999999999', 'hh9999']);

    it('should format phone number', function (string $value, string $result) {
        expect(Formatter::toPhoneNumber($value))->toBe($result);
    })->with([
        ['31988887777', '(31) 98888-7777'],
        ['11877776666', '(11) 87777-6666'],
        ['3188887777', '(31) 8888-7777'],
        ['1177776666', '(11) 7777-6666'],
    ]);
});

describe('toCep tests', function () {
    it('should throw when cep number is not valid', function (string $value) {
        expect(fn () => Formatter::toCep($value))->toThrow(
            InvalidArgumentException::class,
            "O valor '{$value}', não pode ser transformado em um CEP válido"
        );
    })->with(['3200000', '320000000']);

    it('should be a valid formated CEP number', function () {
        expect(Formatter::toCep('32000000'))->toBe('32000-000');
    });
});

describe('toCpfCnpj tests', function () {
    it('should throw when cpf/cnpj is not valid', function (string $value) {
        expect(fn () => Formatter::toCpfCnpj($value))->toThrow(
            InvalidArgumentException::class,
            'Formato do CPF ou CNPJ informado incorreto'
        );
    })->with(['839623150801', '257265200001071']);

    it('should be a valid formated CPF/CNPJ number', function (string $value, string $result) {
        expect(Formatter::toCpfCnpj($value))->toBe($result);
    })->with([
        ['89108614000103', '89.108.614/0001-03'],
        ['43884825011', '438.848.250-11'],
        ['576.986.060-07', '576.986.060-07'],
        ['13.269.841/0001-40', '13.269.841/0001-40']
    ]);
});

describe('normalizeTextToCompare tests', function () {
    it('should remove all latin chars from string and upper case it', function (string $value, string $result) {
        expect(Formatter::normalizeTextToCompare($value))->toBe($result);
    })->with([
        ['áéíóúÁÉÍÓÚâêîôÂÊÎÔãõÃÕàÀçÇüÜ', 'AEIOUAEIOUAEIOAEIOAOAOAACCUU'],
        ['Foó Bâr', 'FOO BAR'],
        ['~Foó Bâr^`/', 'FOO BAR'],
    ]);
});

describe('toDecimalString tests', function () {
    it('should transform a number into decimal one', function (string $value, string $result) {
        expect(Formatter::toDecimalString($value))->toBe($result);
    })->with([
        ['1', '01'],
        ['01', '01'],
        ['10', '10'],
        ['100', '100'],
    ]);
});

describe('hasOnlyNumbers tests', function () {
    it('should check if a string has only numerical chars', function (string $value, bool $result) {
        expect(Formatter::hasOnlyNumbers($value))->toBe($result);
    })->with([
        ['00foo', false],
        ['90990909', true],
        ['999ó', false],
    ]);
});

describe('toCurrency tests', function () {
    it('should format number into currency', function (int|float $value, string $result) {
        expect(Formatter::toCurrency($value))->toBe($result);
    })->with([
        [9, 'R$ 9,00'],
        [100, 'R$ 100,00'],
        [199.9, 'R$ 199,90'],
    ]);
});

describe('capitalizeWords tests', function () {
    it('should capitalize only the first letter of each word', function (string $value, string $result) {
        expect(Formatter::capitalizeWords($value))->toBe($result);
    });
})->with([
    ['Foo bar CAR', 'Foo Bar Car'],
    ['FOO BAR CAR', 'Foo Bar Car'],
    ['fOo BaR CAR', 'Foo Bar Car'],
]);
