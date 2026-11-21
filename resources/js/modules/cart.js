import $ from 'jquery';
import { lockScroll, unlockScroll } from '../utils/scroll-lock';
import { toast } from './toast';

const ENDPOINTS = {
    show: '/cart',
    store: '/cart/items',
    destroy: (slug) => `/cart/items/${encodeURIComponent(slug)}`,
};

const ERROR_TOAST = ['Operazione non riuscita', { description: 'Si è verificato un problema. Riprova tra qualche istante.' }];

let $drawer = $();
let previouslyFocused = null;
let lastCount = null;

/**
 * Pinta el estado devuelto por el servidor: contador, etiqueta y contenido del panel.
 *
 * @param {{ count: number, badge: string, label: string, html: string }} state
 */
function render(state) {
    const $badge = $('[data-cart-badge]');

    // Se vuelve a montar el contador para repetir su animación, como en el original.
    if (state.count !== lastCount && state.count > 0) {
        $badge.replaceWith($badge.clone().text(state.badge).prop('hidden', false));
    } else {
        $badge.text(state.badge).prop('hidden', state.count === 0);
    }

    $('[data-cart-open]').attr('aria-label', state.label);
    $drawer.find('[data-cart-count]').text(state.count);
    $drawer.find('[data-cart-content]').html(state.html);
    lastCount = state.count;
}

function isOpen() {
    return $drawer.hasClass('cart-drawer--open');
}

function open() {
    if (isOpen()) {
        return;
    }

    previouslyFocused = document.activeElement;
    $drawer.addClass('cart-drawer--open').removeAttr('inert');
    $('[data-cart-open]').attr('aria-expanded', 'true');
    lockScroll();
    $drawer.find('.cart-drawer__close').trigger('focus');
}

function close() {
    if (!isOpen()) {
        return;
    }

    $drawer.removeClass('cart-drawer--open').attr('inert', '');
    $('[data-cart-open]').attr('aria-expanded', 'false');
    unlockScroll();
    previouslyFocused?.focus?.();
}

/**
 * Añade una unidad del producto y muestra la confirmación.
 */
function add(slug, title) {
    $.ajax({ url: ENDPOINTS.store, method: 'POST', data: { slug } })
        .done((state) => {
            render(state);
            toast.success('Aggiunto al carrello', { description: title, duration: 2500 });
        })
        .fail(() => toast.error(...ERROR_TOAST));
}

function remove(slug) {
    $.ajax({ url: ENDPOINTS.destroy(slug), method: 'DELETE' })
        .done(render)
        .fail(() => toast.error(...ERROR_TOAST));
}

/**
 * Relee el carrito (cambios hechos en otra pestaña de la misma sesión).
 */
function refresh() {
    $.getJSON(ENDPOINTS.show).done(render);
}

/**
 * Carrito en la sesión de Laravel: botón del header, panel lateral y botones "Aggiungi al carrello".
 */
export function initCart() {
    $drawer = $('[data-cart-drawer]');

    if (!$drawer.length) {
        return;
    }

    lastCount = Number($drawer.find('[data-cart-count]').text()) || 0;

    $(document).on('click', '[data-cart-open]', open);
    $drawer.on('click', '[data-cart-close]', close);

    $(document).on('click', '[data-add-to-cart]', function (event) {
        // Dentro de una card o de un resultado: no debe navegar.
        event.preventDefault();
        event.stopPropagation();
        add(String($(this).data('addToCart')), String($(this).data('productTitle')));
    });

    $drawer.on('click', '[data-cart-remove]', function () {
        remove(String($(this).data('cartRemove')));
    });

    $(document).on('keydown', (event) => {
        if (event.key === 'Escape' && isOpen()) {
            close();
        }
    });

    // Sincronización aproximada entre pestañas: al volver a esta pestaña se relee el carrito.
    $(window).on('focus', refresh);
    $(window).on('pageshow', (event) => {
        if (event.originalEvent.persisted) {
            refresh();
        }
    });
}
