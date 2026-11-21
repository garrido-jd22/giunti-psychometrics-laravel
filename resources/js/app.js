import $ from 'jquery';

import { setupHttp } from './utils/http';
import { initTheme } from './modules/theme';
import { initToasts } from './modules/toast';
import { initMobileMenu } from './modules/mobile-menu';
import { initCart } from './modules/cart';
import { initHeaderSearch } from './modules/header-search';
import { initLoadables } from './modules/loadable';
import { initCatalog } from './modules/catalog';
import { initContactForm } from './modules/contact-form';

window.$ = window.jQuery = $;

// Cada módulo comprueba si su markup existe en la página antes de inicializarse.
$(() => {
    setupHttp();
    initTheme();
    initToasts();
    initMobileMenu();
    initCart();
    initHeaderSearch();
    initLoadables();
    initCatalog();
    initContactForm();
});
