{{-- Resultados del buscador del header (respuesta HTML de products.search). --}}
@props(['products'])

@if ($products->isEmpty())
    <div class="header-search__empty">
        <x-ui.icon name="search-x" class="header-search__empty-icon" :stroke-width="1.6" />
        <p class="header-search__empty-text">Nessun prodotto trovato.</p>
    </div>
@else
    <ul class="header-search__list" data-header-search-list>
        @foreach ($products as $product)
            <li class="header-search__item">
                <a href="{{ $product->url() }}" class="header-search__link" data-header-search-link>
                    <img src="{{ $product->image }}" alt="" loading="lazy" class="header-search__thumb">
                    <span class="header-search__text">
                        <span class="header-search__title">{{ $product->title }}</span>
                        <span class="header-search__description">{{ $product->description }}</span>
                    </span>
                </a>
                <x-cart.add-button :product="$product" variant="icon" />
            </li>
        @endforeach
    </ul>
@endif
