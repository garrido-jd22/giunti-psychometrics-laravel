<?php

namespace App\Contracts;

use App\DTOs\ProductData;
use Illuminate\Support\Collection;

/**
 * Origen de los productos del catálogo. Hoy son datos de ejemplo en config;
 * cuando exista una API basta con registrar otra implementación.
 */
interface ProductRepository
{
    /**
     * Todos los productos, en el orden del catálogo.
     *
     * @return Collection<int, ProductData>
     */
    public function all(): Collection;

    public function findBySlug(string $slug): ?ProductData;
}
