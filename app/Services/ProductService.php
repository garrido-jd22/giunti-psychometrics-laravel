<?php

namespace App\Services;

use App\Contracts\ProductRepository;
use App\DTOs\ProductData;
use App\DTOs\ProductFilters;
use App\Enums\ProductType;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Consultas y filtrado del catálogo. Única fuente de la lógica de búsqueda:
 * la usan la página del catálogo, el endpoint AJAX y el buscador del header.
 */
class ProductService
{
    public function __construct(private ProductRepository $products) {}

    /**
     * @return Collection<int, ProductData>
     */
    public function all(): Collection
    {
        return $this->products->all();
    }

    public function findBySlug(string $slug): ?ProductData
    {
        return $this->products->findBySlug($slug);
    }

    /**
     * Productos destacados de la home.
     *
     * @return Collection<int, ProductData>
     */
    public function featured(): Collection
    {
        return $this->all()->filter(fn (ProductData $product): bool => $product->featured)->values();
    }

    /**
     * @return Collection<int, ProductData>
     */
    public function byType(ProductType $type): Collection
    {
        return $this->all()->filter(fn (ProductData $product): bool => $product->type === $type)->values();
    }

    /**
     * Número de productos por tipo, para el contador de cada pestaña.
     *
     * @return array<string, int>
     */
    public function countsByType(): array
    {
        return collect(ProductType::cases())
            ->mapWithKeys(fn (ProductType $type): array => [$type->value => $this->byType($type)->count()])
            ->all();
    }

    /**
     * Aplica categoría, formato y búsqueda (título, descripción o autor).
     *
     * @param  Collection<int, ProductData>  $products
     * @return Collection<int, ProductData>
     */
    public function filter(Collection $products, ProductFilters $filters): Collection
    {
        $query = $this->normalize($filters->search);

        return $products->filter(function (ProductData $product) use ($filters, $query): bool {
            if ($filters->category !== null && $product->category !== $filters->category) {
                return false;
            }

            if ($filters->format !== null && $product->format !== $filters->format) {
                return false;
            }

            if ($query === '') {
                return true;
            }

            return str_contains(
                $this->normalize("{$product->title} {$product->description} {$product->author}"),
                $query,
            );
        })->values();
    }

    /**
     * Búsqueda solo por título, usada en el popover del header.
     *
     * @return Collection<int, ProductData>
     */
    public function searchByName(string $search, ?int $limit = null): Collection
    {
        $query = $this->normalize($search);

        if ($query === '') {
            return collect();
        }

        $results = $this->all()
            ->filter(fn (ProductData $product): bool => str_contains($this->normalize($product->title), $query))
            ->values();

        return $limit === null ? $results : $results->take($limit);
    }

    /**
     * Minúsculas y sin acentos, para que "eta" encuentre "Età".
     */
    private function normalize(string $text): string
    {
        return Str::lower(trim(Str::ascii($text)));
    }
}
