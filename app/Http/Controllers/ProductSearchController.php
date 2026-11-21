<?php

namespace App\Http\Controllers;

use App\DTOs\ProductData;
use App\Services\ProductService;
use App\Support\Formatter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;

class ProductSearchController extends Controller
{
    /**
     * Búsqueda por nombre para el popover del header: /products/search?q=...
     * Devuelve los datos y la lista ya renderizada por Blade.
     */
    public function __invoke(Request $request, ProductService $products): JsonResponse
    {
        $results = $products->searchByName(
            (string) $request->query('q', ''),
            config('products.search.header_limit'),
        );

        return response()->json([
            'count' => $results->count(),
            'results' => $results->map(fn (ProductData $product): array => [
                'slug' => $product->slug,
                'title' => $product->title,
                'description' => $product->description,
                'image' => $product->image,
                'url' => $product->url(),
                'price' => Formatter::euro($product->price),
            ])->all(),
            'html' => Blade::render('<x-layout.header-search-results :products="$products" />', ['products' => $results]),
        ]);
    }
}
