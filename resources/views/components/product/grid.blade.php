{{-- Grid de productos: skeleton, estado vacío o cards con entrada escalonada (80 ms). --}}
@props([
    'products',
    'emptyMessage' => 'Nessun prodotto corrisponde ai filtri selezionati.',
    'loading' => false,
    'skeletonCount' => 6,
])

@if ($loading)
    <div class="product-grid" role="status" aria-label="Caricamento prodotti">
        @for ($i = 0; $i < $skeletonCount; $i++)
            <x-product.card-skeleton />
        @endfor
    </div>
@elseif ($products->isEmpty())
    <div class="product-grid product-grid--empty">
        <x-ui.icon name="search-x" class="product-grid__empty-icon" :stroke-width="1.6" />
        <p class="product-grid__empty-text">{{ $emptyMessage }}</p>
    </div>
@else
    <div class="product-grid">
        @foreach ($products as $product)
            <div class="product-grid__item u-animate-fade-up" style="animation-delay: {{ $loop->index * 80 }}ms">
                <x-product.card :product="$product" />
            </div>
        @endforeach
    </div>
@endif
