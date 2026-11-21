{{-- Formulario GET: sin JS recarga /products con los filtros; con JS se actualiza por AJAX. --}}
@use('App\Enums\ProductCategory')

@props(['type', 'filters'])

@php($activeCategory = $filters->category?->value ?? 'all')

<form
    method="GET"
    action="{{ route('products.index') }}"
    class="product-filters"
    role="search"
    data-product-filters
>
    <input type="hidden" name="{{ config('products.type_param') }}" value="{{ $type->value }}">

    <div class="product-filters__bar">
        <div class="product-filters__fields">
            <div class="product-filters__search">
                <x-ui.icon name="search" class="product-filters__search-icon" />
                <input
                    type="search"
                    name="search"
                    value="{{ $filters->search }}"
                    class="product-filters__search-input"
                    placeholder="Cerca per nome..."
                    aria-label="Cerca prodotti"
                    data-filter-search
                >
            </div>

            <span class="product-filters__divider" aria-hidden="true"></span>

            <div class="product-filters__selects">
                <x-ui.select
                    name="format"
                    label="Formato prodotto"
                    icon="layers"
                    variant="primary"
                    :options="$type->formatOptions()"
                    :value="$filters->format?->value ?? 'all'"
                />
                <x-ui.select
                    name="category"
                    label="Categoria"
                    icon="tag"
                    :options="ProductCategory::options()"
                    :value="$activeCategory"
                />
            </div>
        </div>

        <button type="button" class="product-filters__show" data-filters-show>
            <x-ui.icon name="sliders-horizontal" />
            Mostra filtri
        </button>

        <div class="product-filters__controls">
            <button type="button" class="product-filters__hide" data-filters-hide>Nascondi</button>

            <button type="button" class="product-filters__reset" data-filters-reset @disabled(! $filters->hasActiveFilters())>
                Reimposta filtri
            </button>
        </div>
    </div>

    <div class="product-filters__chips">
        @foreach (ProductCategory::options() as $value => $label)
            @php($isActive = $value === $activeCategory)
            <button
                type="button"
                @class(['product-filters__chip', 'product-filters__chip--active' => $isActive])
                aria-pressed="{{ $isActive ? 'true' : 'false' }}"
                data-filter-chip="{{ $value }}"
            >
                {{ $label }}
            </button>
        @endforeach
    </div>
</form>
