import $ from 'jquery';

const STORAGE_KEY = 'theme';
const DARK_CLASS = 'dark';

/** @returns {'light'|'dark'|null} Tema guardado por el usuario, o null si nunca eligió. */
function getStoredTheme() {
    try {
        const value = localStorage.getItem(STORAGE_KEY);
        return value === 'light' || value === 'dark' ? value : null;
    } catch {
        return null;
    }
}

/** @returns {'light'|'dark'} Preferencia del sistema operativo. */
function getSystemTheme() {
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}

/** @returns {'light'|'dark'} Tema aplicado actualmente en el DOM. */
function getAppliedTheme() {
    return document.documentElement.classList.contains(DARK_CLASS) ? 'dark' : 'light';
}

/** Pone o quita la clase `dark` en <html> y actualiza los botones. */
function applyTheme(theme) {
    const root = document.documentElement;
    root.classList.toggle(DARK_CLASS, theme === 'dark');
    root.style.colorScheme = theme;
    syncToggles(theme);
}

/** Estado accesible del interruptor (role="switch"). */
function syncToggles(theme) {
    const isDark = theme === 'dark';

    $('[data-theme-toggle]')
        .attr('aria-checked', String(isDark))
        .attr('aria-label', isDark ? 'Attiva tema chiaro' : 'Attiva tema scuro')
        .attr('title', isDark ? 'Tema chiaro' : 'Tema scuro');
}

function setTheme(theme) {
    applyTheme(theme);
    try {
        localStorage.setItem(STORAGE_KEY, theme);
    } catch {
        // Sin localStorage: el tema solo dura la sesión.
    }
}

/**
 * Cambio de tema claro/oscuro. El tema inicial ya lo aplica el script inline
 * del <head>; aquí se gestionan el botón y la sincronización entre pestañas.
 */
export function initTheme() {
    syncToggles(getAppliedTheme());

    $(document).on('click', '[data-theme-toggle]', () => {
        setTheme(getAppliedTheme() === 'dark' ? 'light' : 'dark');
    });

    // Cambio hecho en otra pestaña.
    $(window).on('storage', (event) => {
        if (event.originalEvent.key === STORAGE_KEY) {
            applyTheme(getStoredTheme() ?? getSystemTheme());
        }
    });
}
