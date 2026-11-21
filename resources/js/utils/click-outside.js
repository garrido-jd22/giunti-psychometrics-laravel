import $ from 'jquery';

/**
 * Ejecuta `handler` al hacer clic/tocar fuera de `$element`.
 * Devuelve una función para quitar el listener.
 *
 * @param {JQuery} $element
 * @param {() => void} handler
 * @returns {() => void}
 */
export function onClickOutside($element, handler) {
    const namespace = `pointerdown.outside${Math.random().toString(36).slice(2)}`;

    $(document).on(namespace, (event) => {
        if (!$element[0]?.contains(event.target)) {
            handler();
        }
    });

    return () => $(document).off(namespace);
}
