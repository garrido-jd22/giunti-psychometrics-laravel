import $ from 'jquery';
import { onClickOutside } from '../utils/click-outside';

/**
 * Menú de navegación mobile (< 1024px): botón hamburguesa, Esc y clic fuera cierran.
 */
export function initMobileMenu() {
    const $menu = $('[data-mobile-menu]');

    if (!$menu.length) {
        return;
    }

    const $toggle = $menu.find('[data-mobile-menu-toggle]');
    const $panel = $menu.find('[data-mobile-menu-panel]');
    let removeClickOutside = null;

    const setOpen = (isOpen) => {
        $menu.toggleClass('mobile-menu--open', isOpen);
        $panel.prop('hidden', !isOpen);
        $toggle
            .attr('aria-expanded', String(isOpen))
            .attr('aria-label', isOpen ? 'Chiudi menu' : 'Apri menu');

        removeClickOutside?.();
        removeClickOutside = isOpen ? onClickOutside($menu, () => setOpen(false)) : null;
    };

    $toggle.on('click', () => setOpen(!$menu.hasClass('mobile-menu--open')));
    $menu.on('click', '[data-mobile-menu-close]', () => setOpen(false));

    $(document).on('keydown', (event) => {
        if (event.key === 'Escape' && $menu.hasClass('mobile-menu--open')) {
            setOpen(false);
        }
    });
}
