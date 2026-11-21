@use('App\Support\Formatter')

@props(['line'])

@php($product = $line->product)

<li class="cart-item u-animate-fade-in">
    <img src="{{ $product->image }}" alt="" loading="lazy" class="cart-item__image">

    <div class="cart-item__body">
        <div class="cart-item__top">
            <a href="{{ $product->url() }}" class="cart-item__title" data-cart-close>{{ $product->title }}</a>
            <button
                type="button"
                class="cart-item__remove"
                aria-label="Rimuovi {{ $product->title }} dal carrello"
                data-cart-remove="{{ $product->slug }}"
            >
                <x-ui.icon name="trash-2" />
            </button>
        </div>

        <p class="cart-item__format">{{ $product->format->label() }}</p>

        <div class="cart-item__bottom">
            <span class="cart-item__quantity">Qtà {{ $line->quantity }}</span>
            <span class="cart-item__subtotal">{{ Formatter::euro($line->subtotal) }}</span>
        </div>
    </div>
</li>
