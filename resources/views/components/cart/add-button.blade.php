{{-- Variantes: pill (card), icon (buscador) y block (detalle). --}}
@props([
    'product',
    'variant' => 'pill',
])

@php($isIcon = $variant === 'icon')

<button
    type="button"
    {{ $attributes->merge(['class' => "button button--primary button--press button--{$variant}"]) }}
    @if ($isIcon) aria-label="Aggiungi {{ $product->title }} al carrello" @endif
    data-add-to-cart="{{ $product->slug }}"
    data-product-title="{{ $product->title }}"
>
    <x-ui.icon name="shopping-cart" :stroke-width="$isIcon ? 1.8 : 2" />
    @unless ($isIcon)
        Aggiungi al carrello
    @endunless
</button>
