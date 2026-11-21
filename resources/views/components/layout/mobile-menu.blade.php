@use('App\Support\Link')

{{-- Menú mobile (< 1024px). Se abre con la clase mobile-menu--open (resources/js/modules/mobile-menu.js). --}}
<div class="mobile-menu" data-mobile-menu>
    <button
        type="button"
        class="icon-button mobile-menu__toggle"
        aria-label="Apri menu"
        aria-expanded="false"
        aria-controls="mobile-menu-panel"
        data-mobile-menu-toggle
    >
        <x-ui.icon name="menu" class="mobile-menu__icon mobile-menu__icon--open" :stroke-width="1.8" />
        <x-ui.icon name="x" class="mobile-menu__icon mobile-menu__icon--close" :stroke-width="1.8" />
    </button>

    <div id="mobile-menu-panel" class="mobile-menu__panel" hidden data-mobile-menu-panel>
        <nav aria-label="Navigazione mobile">
            <ul class="mobile-menu__list">
                @foreach (config('site.navigation') as $item)
                    <li>
                        <a href="{{ Link::url($item) }}" class="mobile-menu__link" data-mobile-menu-close>
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <button type="button" class="mobile-menu__login" data-mobile-menu-close>Accedi</button>
    </div>
</div>
