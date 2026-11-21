<?php

namespace App\Http\Controllers;

use App\DTOs\ProductFilters;
use App\Enums\ProductType;
use App\Services\ProductService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function __construct(private ProductService $products) {}

    /**
     * Catálogo: pestaña por ?type= y filtros por ?search=&category=&format=.
     */
    public function index(Request $request): View
    {
        $type = ProductType::fromQuery($request->query(config('products.type_param')));
        $filters = ProductFilters::fromQuery($request->query(), $type);
        $productsOfType = $this->products->byType($type);

        return view('pages.products.index', [
            'type' => $type,
            'filters' => $filters,
            'counts' => $this->products->countsByType(),
            'totalCount' => $productsOfType->count(),
            'products' => $this->products->filter($productsOfType, $filters),
        ]);
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
