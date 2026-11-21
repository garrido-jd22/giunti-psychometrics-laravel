<?php

use App\Support\Formatter;
use Carbon\CarbonImmutable;

it('formats euros like Intl it-IT', function (float $value, string $expected) {
    expect(Formatter::euro($value))->toBe($expected);
})->with([
    [320, "320,00\u{00A0}€"],
    [99.9, "99,90\u{00A0}€"],
    [1234.5, "1234,50\u{00A0}€"],
    [12345.6, "12.345,60\u{00A0}€"],
]);

it('formats dates with the italian short month', function (string $iso, string $expected) {
    expect(Formatter::date(CarbonImmutable::parse($iso, 'UTC')))->toBe($expected);
})->with([
    ['2026-12-10', '10 dic 2026'],
    ['2027-01-15', '15 gen 2027'],
    ['2027-02-03', '03 feb 2027'],
]);

it('returns at most two uppercase initials', function () {
    expect(Formatter::initials('Team Clinico'))->toBe('TC')
        ->and(Formatter::initials('Team Età Evolutiva'))->toBe('TE')
        ->and(Formatter::initials('area, formazione'))->toBe('AF');
});
