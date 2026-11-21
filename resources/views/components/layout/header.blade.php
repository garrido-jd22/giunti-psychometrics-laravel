@use('App\Support\Link')

<header class="header">
    <div class="header__bar u-animate-fade-down">
        <x-ui.brand class="header__brand" />

        <nav class="header__nav" aria-label="Navigazione principale">
            @foreach (config('site.navigation') as $item)
                <a href="{{ Link::url($item) }}" class="header__link">{{ $item['label'] }}</a>
            @endforeach
        </nav>

        <div class="header__actions">
            <x-layout.header-search />

            <button type="button" class="icon-button header__search-button" aria-label="Cerca prodotti">
                <x-ui.icon name="search" :stroke-width="1.8" />
            </button>

            <x-ui.theme-toggle />

            <x-cart.button />

            <button type="button" class="button button--primary header__login">Accedi</button>

            <x-layout.mobile-menu />
        </div>
    </div>
</header>
