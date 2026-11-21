<?php

/*
|--------------------------------------------------------------------------
| Catálogo: parámetros, ventajas y búsqueda
|--------------------------------------------------------------------------
*/

return [

    /** Query param que elige la pestaña: /products?type=test */
    'type_param' => 'type',

    /** Ventajas junto al botón de compra en el detalle, por tipo de producto. */
    'perks' => [
        'test' => ['Materiale ufficiale e aggiornato', 'Spedizione gratuita in 48 ore', "Assistenza all'uso dello strumento"],
        'course' => ['Attestato di partecipazione', 'Accesso ai materiali del corso', 'Docenti esperti del settore'],
    ],

    'search' => [
        /** Espera tras la última tecla antes de aplicar la búsqueda (ms). */
        'debounce_ms' => 500,

        /** Tiempo que se muestra el skeleton una vez aplicada la búsqueda (ms). */
        'loading_ms' => 500,

        /** Duración de la "consulta inicial" simulada al cargar la página (ms). */
        'initial_load_ms' => 800,

        /** Máximo de resultados en el popover del header. */
        'header_limit' => 5,
    ],

];
