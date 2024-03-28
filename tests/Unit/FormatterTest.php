<?php

use Jonasgn\LaravelCommons\Formatter;

describe('Method: toOnlyNumbers', function () {
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

describe('Method: toPhoneNumber', function () {
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

describe('Method: toCep', function () {
    it('should throw when cep number is not valid', function (string $value) {
        expect(fn () => Formatter::toCep($value))->toThrow(
            InvalidArgumentException::class,
            "O valor '{$value}', não pode ser transformado em um CEP válido"
        );
    })->with(['3200000', '320000000']);

    it('should be a valid formated cep number', function () {
        expect(Formatter::toCep('32000000'))->toBe('32000-000');
    });
});
