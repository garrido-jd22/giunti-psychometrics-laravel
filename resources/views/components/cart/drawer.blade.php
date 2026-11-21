<div class="cart-drawer" inert data-cart-drawer>
    <div class="cart-drawer__backdrop" aria-hidden="true" data-cart-close></div>

    <aside class="cart-drawer__panel" role="dialog" aria-modal="true" aria-labelledby="cart-drawer-title">
        <header class="cart-drawer__header">
            <div class="cart-drawer__heading">
                <h2 id="cart-drawer-title" class="cart-drawer__title">Il tuo carrello</h2>
                <span class="cart-drawer__count" data-cart-count>{{ $cart->count }}</span>
            </div>
            <button type="button" class="cart-drawer__close" aria-label="Chiudi carrello" data-cart-close>
                <x-ui.icon name="x" />
            </button>
        </header>

        <div class="cart-drawer__content" data-cart-content>
            <x-cart.drawer-content :cart="$cart" />
        </div>
    </aside>
</div>
