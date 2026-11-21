import $ from 'jquery';

/**
 * Configura las peticiones AJAX de jQuery: token CSRF de Laravel y respuestas JSON.
 */
export function setupHttp() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'X-Requested-With': 'XMLHttpRequest',
            Accept: 'application/json',
        },
    });
}

/**
 * Espera al menos `ms` antes de resolver la petición (estados de carga perceptibles,
 * como el skeleton o el "Invio in corso..." del proyecto original).
 *
 * @param {JQuery.jqXHR} request
 * @param {number} ms
 * @returns {Promise<any>}
 */
export function withMinimumDelay(request, ms) {
    const delay = new Promise((resolve) => setTimeout(resolve, ms));

    return Promise.all([request, delay]).then(([response]) => response);
}
