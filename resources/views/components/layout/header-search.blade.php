{{-- Los resultados llegan por AJAX desde products.search. --}}
<div class="header-search" role="search" data-header-search data-url="{{ route('products.search') }}">
    <x-ui.icon name="search" class="header-search__icon" />

    <input
        type="search"
        name="q"
        class="header-search__input"
        placeholder="Cerca prodotti..."
        role="combobox"
        aria-autocomplete="list"
        aria-label="Cerca prodotti per nome"
        aria-expanded="false"
        aria-controls="header-search-popover"
        autocomplete="off"
        data-header-search-input
    >

    <div id="header-search-popover" class="header-search__popover" hidden data-header-search-popover>
        <p class="header-search__status" aria-live="polite" data-header-search-status></p>
        <div data-header-search-results></div>
    </div>

    <template data-header-search-skeleton>
        <ul class="header-search__list" role="status" aria-label="Caricamento risultati">
            @for ($i = 0; $i < 3; $i++)
                <li class="header-search__item header-search__item--skeleton u-pulse" aria-hidden="true">
                    <span class="header-search__thumb skeleton-block skeleton-block--soft"></span>
                    <span class="header-search__skeleton-text">
                        <span class="skeleton-block header-search__skeleton-line header-search__skeleton-line--title"></span>
                        <span class="skeleton-block header-search__skeleton-line header-search__skeleton-line--full"></span>
                        <span class="skeleton-block header-search__skeleton-line header-search__skeleton-line--short"></span>
                    </span>
                    <span class="skeleton-block header-search__skeleton-action"></span>
                </li>
            @endfor
        </ul>
    </template>
</div>
