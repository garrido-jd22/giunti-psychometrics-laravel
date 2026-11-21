import $ from 'jquery';
import { onClickOutside } from '../utils/click-outside';

/**
 * Select accesible (listbox WAI-ARIA) con ratón y teclado.
 * Al elegir una opción distinta dispara `select:change` con el nuevo valor.
 *
 * @param {JQuery} $select Elemento [data-select]
 */
function setupSelect($select) {
    if ($select.data('select')) {
        return;
    }

    const $input = $select.find('[data-select-input]');
    const $trigger = $select.find('[data-select-trigger]');
    const $label = $select.find('[data-select-value]');
    const $listbox = $select.find('[data-select-listbox]');
    const $options = $listbox.children('[role="option"]');
    const lastIndex = $options.length - 1;
    let isOpen = false;
    let activeIndex = -1;
    let removeClickOutside = null;

    const selectedIndex = () => $options.toArray().findIndex((option) => String($(option).data('value')) === String($input.val()));

    const renderActive = () => {
        $options.removeClass('select__option--active');

        if (isOpen && activeIndex >= 0) {
            const option = $options.eq(activeIndex).addClass('select__option--active')[0];
            $trigger.attr('aria-activedescendant', option.id);
            option.scrollIntoView({ block: 'nearest' });
        } else {
            $trigger.removeAttr('aria-activedescendant');
        }
    };

    const open = () => {
        isOpen = true;
        activeIndex = Math.max(selectedIndex(), 0);
        $select.addClass('select--open');
        $listbox.prop('hidden', false);
        $trigger.attr('aria-expanded', 'true');
        removeClickOutside = onClickOutside($select, close);
        renderActive();
    };

    function close() {
        isOpen = false;
        $select.removeClass('select--open');
        $listbox.prop('hidden', true);
        $trigger.attr('aria-expanded', 'false');
        removeClickOutside?.();
        removeClickOutside = null;
        renderActive();
    }

    /** Actualiza el valor sin disparar eventos (sincronización desde fuera). */
    const setValue = (value) => {
        $input.val(value);
        $options.each((_, option) => {
            const isSelected = String($(option).data('value')) === String(value);
            $(option).toggleClass('select__option--selected', isSelected).attr('aria-selected', String(isSelected));
        });
        $label.text($options.eq(selectedIndex()).children('span').first().text() || $trigger.attr('aria-label'));
    };

    const choose = (index) => {
        const value = $options.eq(index).data('value');

        if (value !== undefined && String(value) !== String($input.val())) {
            setValue(value);
            $select.trigger('select:change', [String(value)]);
        }

        close();
    };

    $trigger.on('click', () => (isOpen ? close() : open()));

    $options
        .on('mousedown', (event) => event.preventDefault())
        .on('mouseenter', function () {
            activeIndex = $options.index(this);
            renderActive();
        })
        .on('click', function () {
            choose($options.index(this));
        });

    $trigger.on('keydown', (event) => {
        switch (event.key) {
            case 'ArrowDown':
                event.preventDefault();
                if (isOpen) {
                    activeIndex = Math.min(activeIndex + 1, lastIndex);
                    renderActive();
                } else {
                    open();
                }
                break;
            case 'ArrowUp':
                event.preventDefault();
                if (isOpen) {
                    activeIndex = Math.max(activeIndex - 1, 0);
                    renderActive();
                } else {
                    open();
                }
                break;
            case 'Home':
                if (isOpen) {
                    event.preventDefault();
                    activeIndex = 0;
                    renderActive();
                }
                break;
            case 'End':
                if (isOpen) {
                    event.preventDefault();
                    activeIndex = lastIndex;
                    renderActive();
                }
                break;
            case 'Enter':
            case ' ':
                event.preventDefault();
                if (isOpen) {
                    choose(activeIndex);
                } else {
                    open();
                }
                break;
            case 'Escape':
                if (isOpen) {
                    event.preventDefault();
                    close();
                }
                break;
            case 'Tab':
                close();
                break;
        }
    });

    $select.data('select', { setValue });
}

/**
 * Inicializa los selects dentro de `root`.
 *
 * @param {Document|Element|JQuery} [root]
 */
export function initSelects(root = document) {
    $(root).find('[data-select]').each((_, element) => setupSelect($(element)));
}

/**
 * Cambia el valor de un select ya inicializado sin disparar `select:change`.
 *
 * @param {JQuery} $select
 * @param {string} value
 */
export function setSelectValue($select, value) {
    $select.data('select')?.setValue(value);
}
