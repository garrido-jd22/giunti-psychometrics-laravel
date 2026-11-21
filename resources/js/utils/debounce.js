/**
 * Ejecuta `callback` solo cuando deja de llamarse durante `delay` ms.
 * `cancel()` descarta la llamada pendiente.
 *
 * @template {(...args: any[]) => void} T
 * @param {T} callback
 * @param {number} delay
 * @returns {T & { cancel: () => void }}
 */
export function debounce(callback, delay) {
    let timer = null;

    const debounced = (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => callback(...args), delay);
    };

    debounced.cancel = () => clearTimeout(timer);

    return debounced;
}
