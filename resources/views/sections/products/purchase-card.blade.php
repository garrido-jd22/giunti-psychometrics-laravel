@use('App\Support\Formatter')

@php($discount = $product->discountPercent())

<div class="purchase-card">
    <p class="purchase-card__label">Prezzo</p>

    <div class="purchase-card__prices">
        <span class="purchase-card__price">{{ Formatter::euro($product->price) }}</span>
        @if ($product->originalPrice !== null)
            <span class="purchase-card__original">{{ Formatter::euro($product->originalPrice) }}</span>
        @endif
        @if ($discount !== null)
            <span class="purchase-card__discount">-{{ $discount }}%</span>
        @endif
    </div>
    <p class="purchase-card__tax">IVA inclusa</p>

    <div class="purchase-card__action">
        <x-cart.add-button :product="$product" variant="block" />
    </div>

    <ul class="purchase-card__perks">
        @foreach ($perks as $perk)
            <li class="purchase-card__perk">
                <x-ui.icon name="circle-check" class="purchase-card__perk-icon" />
                {{ $perk }}
            </li>
        @endforeach
    </ul>

    <a href="{{ $product->type->url() }}" class="purchase-card__back">
        <x-ui.icon name="arrow-left" />
        Torna al catalogo
    </a>
</div>
