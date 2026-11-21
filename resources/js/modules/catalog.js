import $ from 'jquery';
import { debounce } from '../utils/debounce';
import { withMinimumDelay } from '../utils/http';
import { initSelects, setSelectValue } from './select';
import { searchTiming, startInitialLoading } from './loadable';

const ALL = 'all';

/**
 * Reemplaza el grid conservando las cards que siguen en el resultado
 * (no repiten la animación de entrada, como en el original).
 */
function swapGrid($container, html, keepExisting) {
    const $next = $($.parseHTML(String(html).trim()));

    if (keepExisting) {
        const existing = new Map();
        $container.find('[data-product-slug]').each((_, item) => existing.set(String($(item).data('productSlug')), item));
        $next.find('[data-product-slug]').each((_, item) => {
            const previous = existing.get(String($(item).data('productSlug')));
            if (previous) {
                item.replaceWith(previous);
            }
        });
    }

    $container.empty().append($next);
}

/**
 * Filtros de un catálogo: buscador con debounce, selects, chips, ocultar y reimpostare.
 *
 * @param {JQuery} $catalog Elemento [data-catalog]
 */
function setupCatalog($catalog) {
    const timing = searchTiming();
    const $form = $catalog.find('[data-product-filters]');
    const $search = $form.find('[data-filter-search]');
    const $reset = $form.find('[data-filters-reset]');
    const $results = $catalog.find('[data-catalog-results]');
    const $status = $catalog.find('[data-catalog-status]');
    const $formatSelect = $form.find('[data-select]').has('input[name="format"]');
    const $categorySelect = $form.find('[data-select]').has('input[name="category"]');

    const state = {
        type: String($catalog.data('type')),
        search: String($search.val()),
        applied: String($search.val()),
        format: String($formatSelect.find('input').val()),
        category: String($categorySelect.find('input').val()),
    };
    let requestId = 0;

    initSelects($catalog);
    startInitialLoading($catalog);

    const query = () => {
        const params = { type: state.type };
        if (state.applied.trim()) params.search = state.applied.trim();
        if (state.category !== ALL) params.category = state.category;
        if (state.format !== ALL) params.format = state.format;
        return params;
    };

    const hasActiveFilters = () => state.search !== '' || state.category !== ALL || state.format !== ALL;

    const syncControls = () => {
        $reset.prop('disabled', !hasActiveFilters());
        $form.find('[data-filter-chip]').each((_, chip) => {
            const isActive = String($(chip).data('filterChip')) === state.category;
            $(chip).toggleClass('product-filters__chip--active', isActive).attr('aria-pressed', String(isActive));
        });
    };

    /**
     * Pide los resultados al servidor. Con `skeleton` se muestra la carga de una búsqueda.
     */
    const refresh = ({ skeleton }) => {
        const id = ++requestId;
        const request = $.getJSON($catalog.data('url'), query());

        if (skeleton) {
            $catalog.addClass('loadable--loading');
        }

        (skeleton ? withMinimumDelay(request, timing.loading_ms) : request).then((data) => {
            // Respuesta antigua o catálogo ya sustituido (cambio de pestaña).
            if (id !== requestId || !document.contains($catalog[0])) {
                return;
            }

            $status.text(data.status);
            swapGrid($results, data.results, !skeleton);
            $catalog.removeClass('loadable--loading');

            const search = new URLSearchParams(query()).toString();
            history.replaceState(history.state, '', `${location.pathname}?${search}`);
        });
    };

    // Buscador: mientras se escribe los resultados se atenúan; tras 500 ms se aplica.
    const applySearch = debounce(() => {
        state.applied = state.search;
        $catalog.removeClass('loadable--typing');
        refresh({ skeleton: true });
    }, timing.debounce_ms);

    const onSearchChange = () => {
        syncControls();

        if (state.search === state.applied) {
            applySearch.cancel();
            $catalog.removeClass('loadable--typing');
            return;
        }

        $catalog.addClass('loadable--typing');
        applySearch();
    };

    $search.on('input', () => {
        state.search = String($search.val());
        onSearchChange();
    });

    // Enter no recarga la página (el formulario GET solo se usa sin JavaScript).
    $form.on('submit', (event) => event.preventDefault());

    $form.on('select:change', '[data-select]', function (_event, value) {
        const name = $(this).find('[data-select-input]').attr('name');
        state[name] = value;
        syncControls();
        refresh({ skeleton: false });
    });

    $form.on('click', '[data-filter-chip]', function () {
        const value = String($(this).data('filterChip'));

        if (value === state.category) {
            return;
        }

        state.category = value;
        setSelectValue($categorySelect, value);
        syncControls();
        refresh({ skeleton: false });
    });

    $form.on('click', '[data-filters-hide]', () => $form.addClass('product-filters--collapsed'));
    $form.on('click', '[data-filters-show]', () => $form.removeClass('product-filters--collapsed'));

    $reset.on('click', () => {
        state.category = ALL;
        state.format = ALL;
        state.search = '';
        $search.val('');
        setSelectValue($categorySelect, ALL);
        setSelectValue($formatSelect, ALL);
        refresh({ skeleton: false });
        onSearchChange();
    });

    syncControls();
}

/**
 * Cambia de pestaña sin recargar la página: solo se sustituye el catálogo.
 */
function navigate(url, { push }) {
    $.getJSON(url).then((data) => {
        const $catalog = $($.parseHTML(String(data.catalog).trim()));
        const type = String($catalog.data('type'));

        $('[data-catalog]').replaceWith($catalog);
        setupCatalog($catalog);

        $('[data-product-tab]').each((_, tab) => {
            const isActive = String($(tab).data('productTab')) === type;
            $(tab).toggleClass('product-tabs__link--active', isActive);
            isActive ? $(tab).attr('aria-current', 'page') : $(tab).removeAttr('aria-current');
        });

        document.title = data.title;

        if (push) {
            history.pushState({ catalog: true }, '', url);
        }
    });
}

export function initCatalog() {
    const $catalog = $('[data-catalog]');

    if (!$catalog.length) {
        return;
    }

    setupCatalog($catalog);

    $(document).on('click', '[data-product-tab]', function (event) {
        // Ctrl/Cmd + clic y similares abren la pestaña con normalidad.
        if (event.metaKey || event.ctrlKey || event.shiftKey || event.button !== 0) {
            return;
        }

        event.preventDefault();
        navigate(this.href, { push: true });
    });

    // Atrás / adelante del navegador entre pestañas.
    $(window).on('popstate', () => navigate(location.href, { push: false }));
}
