import $ from 'jquery';

/**
 * Tiempos de búsqueda y carga simulada (config/products.php → search), publicados
 * por el layout en <body data-search-timing>.
 *
 * @returns {{ debounce_ms: number, loading_ms: number, initial_load_ms: number }}
 */
export function searchTiming() {
    return { debounce_ms: 500, loading_ms: 500, initial_load_ms: 800, ...($('body').data('searchTiming') ?? {}) };
}

/**
 * Termina la carga simulada de un bloque .loadable: el CSS muestra el skeleton
 * hasta que el bloque recibe la clase loadable--ready.
 *
 * @param {JQuery} $loadable
 */
export function startInitialLoading($loadable) {
    $loadable.removeClass('loadable--ready');
    setTimeout(() => $loadable.addClass('loadable--ready'), searchTiming().initial_load_ms);
}

/**
 * Bloques con carga simulada independientes (p. ej. los destacados de la home).
 */
export function initLoadables() {
    $('[data-loadable]').each((_, element) => startInitialLoading($(element)));
}
