<?php

namespace App\Http\Controllers;

use App\DTOs\ProductFilters;
use App\Enums\ProductType;
use App\Services\ProductService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Blade;

class ProductController extends Controller
{
    public function __construct(private ProductService $products) {}

    /**
     * Catálogo: pestaña por ?type= y filtros por ?search=&category=&format=.
     * Por AJAX devuelve los resultados y el catálogo renderizados (filtros y cambio de pestaña).
     */
    public function index(Request $request): View|JsonResponse
    {
        $type = ProductType::fromQuery($request->query(config('products.type_param')));
        $filters = ProductFilters::fromQuery($request->query(), $type);
        $productsOfType = $this->products->byType($type);
        $products = $this->products->filter($productsOfType, $filters);

        $data = [
            'type' => $type,
            'filters' => $filters,
            'counts' => $this->products->countsByType(),
            'totalCount' => $productsOfType->count(),
            'products' => $products,
            'title' => $type->label().' · Catalogo Prodotti',
            'status' => "{$products->count()} di {$productsOfType->count()} {$type->noun()}",
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'title' => $data['title'],
                'status' => $data['status'],
                'hasActiveFilters' => $filters->hasActiveFilters(),
                'results' => Blade::render('<x-product.grid :products="$products" />', ['products' => $products]),
                'catalog' => view('sections.products.catalog', $data)->render(),
            ]);
        }

        return view('pages.products.index', $data);
    }

    /**
     * Detalle por slug. Si no existe, vista propia de "Prodotto non trovato" con 404.
     */
    public function show(string $slug): View|Response
    {
        $product = $this->products->findBySlug($slug);

        if ($product === null) {
            return response()->view('pages.products.not-found', status: 404);
        }

        return view('pages.products.show', [
            'product' => $product,
            'perks' => config("products.perks.{$product->type->value}", []),
        ]);
    }
}
