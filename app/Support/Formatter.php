<?php

namespace App\Support;

use Carbon\CarbonInterface;

/**
 * Formatos de presentación equivalentes a Intl "it-IT" del proyecto original
 * (la extensión intl no está disponible, así que se calculan a mano).
 */
final class Formatter
{
    /** Espacio de no separación que usa Intl entre la cifra y el símbolo. */
    private const NBSP = "\u{00A0}";

    /**
     * 320 → "320,00 €", 12345.6 → "12.345,60 €".
     * Como en it-IT, el separador de miles solo aparece a partir de 5 cifras.
     */
    public static function euro(float $value): string
    {
        $thousands = abs($value) >= 10000 ? '.' : '';

        return number_format($value, 2, ',', $thousands).self::NBSP.'€';
    }

    /**
     * 2026-12-10 → "10 dic 2026".
     */
    public static function date(CarbonInterface $date): string
    {
        return $date->locale('it')->translatedFormat('d M Y');
    }

    /**
     * "Team Clinico" → "TC". Máximo dos iniciales.
     */
    public static function initials(string $name): string
    {
        $words = preg_split('/[\s,]+/u', $name, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        return collect($words)
            ->take(2)
            ->map(fn (string $word): string => mb_strtoupper(mb_substr($word, 0, 1)))
            ->implode('');
    }
}
