{{-- Bloque loadable: skeleton en la carga inicial y al aplicar una búsqueda (modules/catalog.js). --}}
<div class="catalog loadable" data-catalog data-type="{{ $type->value }}" data-url="{{ route('products.index') }}">
    <x-product.filters :type="$type" :filters="$filters" />

    <p class="catalog__status" aria-live="polite">
        <span class="loadable__loading-text">Ricerca in corso…</span>
        <span class="loadable__ready-text" data-catalog-status>{{ $status }}</span>
    </p>

    <div class="catalog__results">
        <div class="loadable__skeleton">
            <x-product.grid :products="$products" loading :skeleton-count="min($totalCount, 6) ?: 3" />
        </div>
        <div class="loadable__content" data-catalog-results>
            <x-product.grid :products="$products" />
        </div>
    </div>
</div>
