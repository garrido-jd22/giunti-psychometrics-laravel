<?php

namespace App\Enums;

/**
 * Tipo de producto: decide la pestaña del catálogo (/products?type=...).
 */
enum ProductType: string
{
    case Test = 'test';
    case Course = 'course';

    /**
     * Tipo usado cuando la URL no indica uno válido (/products).
     */
    public static function default(): self
    {
        return self::Test;
    }

    /**
     * Convierte el query param `?type=` en un tipo válido.
     */
    public static function fromQuery(mixed $value): self
    {
        $raw = is_array($value) ? ($value[0] ?? null) : $value;

        return is_string($raw) ? (self::tryFrom($raw) ?? self::default()) : self::default();
    }

    /**
     * Etiqueta de la pestaña.
     */
    public function label(): string
    {
        return match ($this) {
            self::Test => 'Test',
            self::Course => 'Formazione',
        };
    }

    /**
     * Sustantivo plural usado en el contador ("3 di 5 corsi").
     */
    public function noun(): string
    {
        return match ($this) {
            self::Test => 'test',
            self::Course => 'corsi',
        };
    }

    /**
     * Icono de la pestaña (resources/views/icons).
     */
    public function icon(): string
    {
        return match ($this) {
            self::Test => 'clipboard-list',
            self::Course => 'graduation-cap',
        };
    }

    /**
     * Formatos disponibles para este tipo (un test no es "In presenza").
     *
     * @return list<ProductFormat>
     */
    public function formats(): array
    {
        return match ($this) {
            self::Test => [ProductFormat::Cartaceo, ProductFormat::Digitale],
            self::Course => [ProductFormat::CorsoOnline, ProductFormat::InPresenza],
        };
    }

    /**
     * Opciones del select de formato ("all" = sin filtro).
     *
     * @return array<string, string>
     */
    public function formatOptions(): array
    {
        $options = ['all' => 'Tutti i formati'];

        foreach ($this->formats() as $format) {
            $options[$format->value] = $format->label();
        }

        return $options;
    }

    /**
     * URL del catálogo filtrado por este tipo.
     */
    public function url(): string
    {
        return route('products.index', [config('products.type_param') => $this->value]);
    }
}
