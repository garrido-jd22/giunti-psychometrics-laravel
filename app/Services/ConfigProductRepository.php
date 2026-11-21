<?php

namespace App\Services;

use App\Contracts\ProductRepository;
use App\DTOs\ProductData;
use Illuminate\Support\Collection;

/**
 * Lee los productos de ejemplo de config/catalog.php.
 */
class ConfigProductRepository implements ProductRepository
{
    /** @var Collection<string, ProductData>|null Índice por slug, creado una sola vez. */
    private ?Collection $productsBySlug = null;

    /**
     * @return Collection<int, ProductData>
     */
    public function all(): Collection
    {
        return $this->index()->values();
    }

    public function findBySlug(string $slug): ?ProductData
    {
        return $this->index()->get($slug);
    }

    /**
     * @return Collection<string, ProductData>
     */
    private function index(): Collection
    {
        return $this->productsBySlug ??= collect(config('catalog.products', []))
            ->map(fn (array $product): ProductData => ProductData::fromArray($product))
            ->keyBy('slug');
    }
}
