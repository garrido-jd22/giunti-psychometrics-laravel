<?php

namespace App\DTOs;

/**
 * Línea del carrito con el producto ya resuelto, lista para pintar.
 */
final readonly class CartLine
{
    public float $subtotal;

    public function __construct(
        public ProductData $product,
        public int $quantity,
    ) {
        $this->subtotal = $product->price * $quantity;
    }
}
