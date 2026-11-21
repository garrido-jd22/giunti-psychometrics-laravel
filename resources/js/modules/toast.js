import $ from 'jquery';

const DEFAULT_DURATION = 4000;
const EXIT_ANIMATION_MS = 200;
const MAX_TOASTS = 4;

let $toaster = $();

/**
 * Inicia la animación de salida y después elimina el toast.
 *
 * @param {JQuery} $toast
 */
function dismiss($toast) {
    if ($toast.hasClass('toast--closing')) {
        return;
    }

    clearTimeout($toast.data('timer'));
    $toast.addClass('toast--closing');
    setTimeout(() => $toast.remove(), EXIT_ANIMATION_MS);
}

/**
 * Reinicia la barra de progreso (se vuelve a montar, como en el original).
 */
function restartProgress($toast, paused) {
    const $progress = $toast.find('[data-toast-progress]');
    const $fresh = $progress.clone();

    $progress.replaceWith($fresh);
    $toast.toggleClass('toast--paused', paused);
}

/**
 * Programa el cierre automático tras `duration` ms (se reinicia al quitar el ratón).
 */
function schedule($toast, duration) {
    clearTimeout($toast.data('timer'));
    $toast.data('timer', setTimeout(() => dismiss($toast), duration));
}

/**
 * Muestra un toast.
 *
 * @param {'success'|'error'|'warning'|'info'|'primary'|'secondary'} variant
 * @param {string} title
 * @param {{ description?: string, duration?: number }} [options]
 * @returns {JQuery|null}
 */
function show(variant, title, { description, duration = DEFAULT_DURATION } = {}) {
    const template = document.querySelector('[data-toast-template]');

    if (!$toaster.length || !template) {
        return null;
    }

    const $toast = $(template.content.firstElementChild.cloneNode(true));
    const iconTemplate = document.querySelector(`[data-toast-icon-template="${variant}"]`);
    const autoClose = Number.isFinite(duration);

    $toast.addClass(`toast--${variant}`).attr('role', variant === 'error' ? 'alert' : 'status');
    $toast.find('[data-toast-title]').text(title);

    if (iconTemplate) {
        $toast.find('[data-toast-icon]').append(iconTemplate.content.cloneNode(true));
    }

    if (description) {
        $toast.find('[data-toast-description]').text(description);
    } else {
        $toast.find('[data-toast-description]').remove();
    }

    if (autoClose) {
        $toast.find('[data-toast-progress]').css('animation-duration', `${duration}ms`);
        schedule($toast, duration);

        // Pausa con el ratón encima; al salir el tiempo vuelve a empezar.
        $toast.on('mouseenter', () => {
            clearTimeout($toast.data('timer'));
            restartProgress($toast, true);
        });
        $toast.on('mouseleave', () => {
            restartProgress($toast, false);
            schedule($toast, duration);
        });
    } else {
        $toast.find('[data-toast-progress]').remove();
    }

    $toast.on('click', '[data-toast-close]', () => dismiss($toast));

    $toaster.append($toast);

    // Los más antiguos salen si se supera el máximo.
    const $all = $toaster.children('.toast');
    $all.slice(0, Math.max(0, $all.length - MAX_TOASTS)).each((_, element) => {
        clearTimeout($(element).data('timer'));
        $(element).remove();
    });

    return $toast;
}

/**
 * API de notificaciones: toast.success('Titolo', { description, duration }).
 */
export const toast = {
    show,
    success: (title, options) => show('success', title, options),
    error: (title, options) => show('error', title, options),
    warning: (title, options) => show('warning', title, options),
    info: (title, options) => show('info', title, options),
    primary: (title, options) => show('primary', title, options),
    secondary: (title, options) => show('secondary', title, options),
};

export function initToasts() {
    $toaster = $('[data-toaster]');
}
