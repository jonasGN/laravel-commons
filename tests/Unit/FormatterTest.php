<?php

use Jonasgn\LaravelCommons\Formatter;

describe('Method: toOnlyNumbers', function () {
    it('should not be empty', function (string $value) {
        expect($value)->not->toBeEmpty();
    })->with(['9090jjjj', 'uhaush9', 'aijs999asi']);

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
    it('should throw when phone number is invalid', function () {
        expect(fn () => Formatter::toPhoneNumber('9999'))->toThrow(
            InvalidArgumentException::class,
            "O valor '9999', não pode ser transformado em número de telefone"
        );
    });

    it('should format phone number', function (string $value, string $result) {
        expect(Formatter::toPhoneNumber($value))->toBe($result);
    })->with([
        ['31988887777', '(31) 98888-7777'],
        ['11877776666', '(11) 87777-6666'],
        ['3188887777', '(31) 8888-7777'],
        ['1177776666', '(11) 7777-6666'],
    ]);
});
