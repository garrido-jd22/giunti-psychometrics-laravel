import $ from 'jquery';
import { debounce } from '../utils/debounce';
import { onClickOutside } from '../utils/click-outside';
import { withMinimumDelay } from '../utils/http';
import { searchTiming } from './loadable';

/**
 * Buscador por nombre del header (desktop).
 * Tecla → espera 500 ms → skeleton 500 ms (con la petición) → resultados.
 */
export function initHeaderSearch() {
    const $search = $('[data-header-search]');

    if (!$search.length) {
        return;
    }

    const url = $search.data('url');
    const $input = $search.find('[data-header-search-input]');
    const $popover = $search.find('[data-header-search-popover]');
    const $status = $search.find('[data-header-search-status]');
    const $results = $search.find('[data-header-search-results]');
    const skeleton = $search.find('[data-header-search-skeleton]')[0];
    const timing = searchTiming();

    let isOpen = false;
    let applied = '';
    let resultCount = 0;
    let requestId = 0;
    let removeClickOutside = null;

    const hasQuery = () => String($input.val()).trim().length > 0;

    const renderPopover = () => {
        const visible = isOpen && hasQuery();
        $popover.prop('hidden', !visible);
        $input.attr('aria-expanded', String(visible));
    };

    const showSkeleton = () => {
        $status.text('Ricerca in corso…');
        $results.empty().append(skeleton.content.cloneNode(true));
    };

    const setOpen = (open) => {
        isOpen = open;
        removeClickOutside?.();
        removeClickOutside = open ? onClickOutside($search, () => setOpen(false)) : null;
        renderPopover();
    };

    const apply = debounce((query) => {
        applied = query;
        const id = ++requestId;

        if (!query.trim()) {
            resultCount = 0;
            $results.empty();
            return;
        }

        showSkeleton();

        withMinimumDelay($.getJSON(url, { q: query }), timing.loading_ms).then((data) => {
            if (id !== requestId) {
                return;
            }

            resultCount = data.count;
            $status.text(`${data.count} risultati`);
            $results.html(data.html);
        });
    }, timing.debounce_ms);

    $input.on('input', () => {
        const query = String($input.val());
        setOpen(true);

        const isTyping = query !== applied;

        // Mientras se escribe: resultados atenuados o skeleton si aún no hay ninguno.
        if (isTyping && hasQuery()) {
            if (resultCount === 0) {
                showSkeleton();
            } else {
                $results.find('[data-header-search-list]').addClass('header-search__list--typing');
            }
        }

        apply(query);
    });

    $input.on('focus', () => setOpen(true));
    $input.on('keydown', (event) => {
        if (event.key === 'Escape') {
            setOpen(false);
        }
    });

    $search.on('click', '[data-header-search-link]', () => setOpen(false));
}
