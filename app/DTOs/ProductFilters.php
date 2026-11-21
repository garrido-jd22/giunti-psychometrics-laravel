<?php

namespace App\DTOs;

use App\Enums\ProductCategory;
use App\Enums\ProductFormat;
use App\Enums\ProductType;

/**
 * Filtros del catálogo. `null` en categoría o formato significa "Tutti".
 */
final readonly class ProductFilters
{
    public function __construct(
        public string $search = '',
        public ?ProductCategory $category = null,
        public ?ProductFormat $format = null,
    ) {}

    /**
     * Crea los filtros a partir de los query params, descartando valores no válidos
     * (p. ej. un formato que no pertenece al tipo de la pestaña).
     *
     * @param  array<string, mixed>  $query
     */
    public static function fromQuery(array $query, ProductType $type): self
    {
        $format = is_string($query['format'] ?? null) ? ProductFormat::tryFrom($query['format']) : null;

        return new self(
            search: is_string($query['search'] ?? null) ? $query['search'] : '',
            category: is_string($query['category'] ?? null) ? ProductCategory::tryFrom($query['category']) : null,
            format: in_array($format, $type->formats(), true) ? $format : null,
        );
    }

    public function hasActiveFilters(): bool
    {
        return trim($this->search) !== '' || $this->category !== null || $this->format !== null;
    }

    /**
     * Query params no vacíos, para reflejar los filtros en la URL.
     *
     * @return array<string, string>
     */
    public function toQuery(): array
    {
        return array_filter([
            'search' => trim($this->search),
            'category' => $this->category?->value,
            'format' => $this->format?->value,
        ], fn (?string $value): bool => $value !== null && $value !== '');
    }
}
