{{-- Catálogo de una pestaña: filtros, contador y resultados. --}}
<div class="catalog" data-catalog data-url="{{ route('products.index') }}">
    <x-product.filters :type="$type" :filters="$filters" />

    <p class="catalog__status" aria-live="polite" data-catalog-status>
        {{ $products->count() }} di {{ $totalCount }} {{ $type->noun() }}
    </p>

    <div class="catalog__results" data-catalog-results data-initial-loading="{{ min($totalCount, 6) ?: 3 }}">
        <x-product.grid :products="$products" />
    </div>
</div>
