<?php

namespace App\Services;

use App\DTOs\CartLine;
use App\DTOs\CartSummary;
use App\DTOs\ProductData;
use Illuminate\Contracts\Session\Session;

/**
 * Carrito guardado en la sesión de Laravel.
 * Solo se guarda slug => cantidad; los datos del producto se leen del catálogo.
 */
class CartService
{
    private const SESSION_KEY = 'cart';

    public function __construct(
        private Session $session,
        private ProductService $products,
    ) {}

    /**
     * Añade unidades de un producto (si ya existe, suma la cantidad).
     */
    public function add(ProductData $product, int $quantity = 1): void
    {
        $items = $this->items();
        $items[$product->slug] = ($items[$product->slug] ?? 0) + $quantity;

        $this->session->put(self::SESSION_KEY, $items);
    }

    public function remove(string $slug): void
    {
        $items = $this->items();
        unset($items[$slug]);

        $this->session->put(self::SESSION_KEY, $items);
    }

    /**
     * Líneas con el producto resuelto, unidades y total.
     * Si un producto guardado ya no existe en el catálogo, se ignora.
     */
    public function summary(): CartSummary
    {
        $lines = [];

        foreach ($this->items() as $slug => $quantity) {
            $product = $this->products->findBySlug((string) $slug);

            if ($product !== null) {
                $lines[] = new CartLine($product, $quantity);
            }
        }

        return new CartSummary($lines);
    }

    /**
     * @return array<string, int>
     */
    private function items(): array
    {
        $items = $this->session->get(self::SESSION_KEY, []);

        if (! is_array($items)) {
            return [];
        }

        return array_filter($items, fn (mixed $quantity): bool => is_int($quantity) && $quantity > 0);
    }
}
