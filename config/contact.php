<?php

/*
|--------------------------------------------------------------------------
| Contacto: datos visibles y reglas del formulario
|--------------------------------------------------------------------------
*/

return [

    /** id de la sección de contacto (ancla de los botones "Contattaci"). */
    'section_id' => 'contatti',

    'email' => 'info@giuntipsy.com',

    'phone' => [
        'label' => '+39 055 623 6501',
        'href' => 'tel:+390556236501',
        'hours' => 'Disponibile dal lunedì al venerdì, 09:00 – 18:00 CET',
    ],

    'rules' => [
        'first_name_min_length' => 2,
        'message_min_length' => 10,
    ],

    /** Duración mínima del estado "Invio in corso..." (ms). */
    'submit_delay_ms' => 1000,

];
