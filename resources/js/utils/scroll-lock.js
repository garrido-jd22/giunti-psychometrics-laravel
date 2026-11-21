import $ from 'jquery';

let locks = 0;

/**
 * Bloquea el scroll del documento (drawer abierto). Admite bloqueos anidados.
 */
export function lockScroll() {
    locks++;
    $('body').addClass('body--locked');
}

export function unlockScroll() {
    locks = Math.max(0, locks - 1);

    if (locks === 0) {
        $('body').removeClass('body--locked');
    }
}
