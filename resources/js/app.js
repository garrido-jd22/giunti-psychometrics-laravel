import $ from 'jquery';

import { setupHttp } from './utils/http';
import { initTheme } from './modules/theme';

window.$ = window.jQuery = $;

// Cada módulo comprueba si su markup existe en la página antes de inicializarse.
$(() => {
    setupHttp();
    initTheme();
});
