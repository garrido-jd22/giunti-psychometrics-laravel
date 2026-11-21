{{-- $cart lo inyecta el view composer de AppServiceProvider. --}}
<button
    type="button"
    class="icon-button cart-button"
    aria-label="{{ $cart->buttonLabel() }}"
    aria-haspopup="dialog"
    aria-expanded="false"
    data-cart-open
>
    <x-ui.icon name="shopping-cart" :stroke-width="1.8" />
    <span class="cart-button__badge" aria-hidden="true" data-cart-badge @if ($cart->count === 0) hidden @endif>{{ $cart->badge() }}</span>
</button>
