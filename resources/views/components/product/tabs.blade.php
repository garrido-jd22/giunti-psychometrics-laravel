{{-- Pestañas Test / Formazione. Cada una es un enlace (/products?type=...). --}}
@use('App\Enums\ProductType')

@props(['activeType', 'counts'])

<nav class="product-tabs" aria-label="Tipo di prodotto">
    <ul class="product-tabs__list">
        @foreach (ProductType::cases() as $tab)
            @php($isActive = $tab === $activeType)
            <li class="product-tabs__item">
                <a
                    href="{{ $tab->url() }}"
                    @class(['product-tabs__link', 'product-tabs__link--active' => $isActive])
                    @if ($isActive) aria-current="page" @endif
                    data-product-tab="{{ $tab->value }}"
                >
                    <x-ui.icon :name="$tab->icon()" />
                    {{ $tab->label() }}
                    <span class="product-tabs__count">{{ $counts[$tab->value] }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</nav>
