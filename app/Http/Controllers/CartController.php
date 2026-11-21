<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\ProductService;
use App\Support\Formatter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

    /**
     * Estado actual del carrito.
     */
    public function show(): JsonResponse
    {
        return $this->cartResponse();
    }

    /**
     * Añade una unidad de un producto existente.
     */
    public function store(Request $request, ProductService $products): JsonResponse
    {
        $validated = $request->validate(['slug' => ['required', 'string']]);

        $product = $products->findBySlug($validated['slug']);

        abort_if($product === null, 404);

        $this->cart->add($product);

        return $this->cartResponse(201);
    }

    /**
     * Elimina la línea de un producto.
     */
    public function destroy(string $slug): JsonResponse
    {
        $this->cart->remove($slug);

        return $this->cartResponse();
    }

    private function cartResponse(int $status = 200): JsonResponse
    {
        $summary = $this->cart->summary();

        return response()->json([
            'count' => $summary->count,
            'total' => Formatter::euro($summary->total),
        ], $status);
    }
}
