<?php

namespace App\DTOs;

/**
 * Estado del carrito: líneas, unidades totales y total en euros.
 */
final readonly class CartSummary
{
    /** Unidades totales (contador del icono). */
    public int $count;

    public float $total;

    /**
     * @param  list<CartLine>  $lines
     */
    public function __construct(public array $lines)
    {
        $this->count = array_sum(array_map(fn (CartLine $line): int => $line->quantity, $lines));
        $this->total = array_sum(array_map(fn (CartLine $line): float => $line->subtotal, $lines));
    }

    public function isEmpty(): bool
    {
        return $this->lines === [];
    }
}
