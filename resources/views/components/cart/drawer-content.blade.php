{{-- Se vuelve a pintar por AJAX tras cada cambio del carrito. --}}
@use('App\Support\Formatter')

@props(['cart'])

@if ($cart->isEmpty())
    <div class="cart-drawer__empty">
        <span class="cart-drawer__empty-icon">
            <x-ui.icon name="shopping-bag" :stroke-width="1.6" />
        </span>
        <div>
            <p class="cart-drawer__empty-title">Il carrello è vuoto</p>
            <p class="cart-drawer__empty-text">Aggiungi test o corsi dal catalogo.</p>
        </div>
        <a href="{{ route('products.index') }}" class="button button--primary cart-drawer__explore" data-cart-close>
            Esplora il catalogo
        </a>
    </div>
@else
    <ul class="cart-drawer__list">
        @foreach ($cart->lines as $line)
            <x-cart.item :line="$line" />
        @endforeach
    </ul>

    <footer class="cart-drawer__footer">
        <div class="cart-drawer__row">
            <span>Prodotti</span>
            <span>{{ $cart->count }}</span>
        </div>
        <div class="cart-drawer__row cart-drawer__row--total">
            <span class="cart-drawer__total-label">Totale</span>
            <span class="cart-drawer__total">{{ Formatter::euro($cart->total) }}</span>
        </div>
        <div class="cart-drawer__actions">
            <button type="button" class="cart-drawer__checkout">
                Procedi all'acquisto
                <x-ui.icon name="arrow-right" />
            </button>
            <button type="button" class="cart-drawer__review">Verifica il carrello</button>
        </div>
    </footer>
@endif
