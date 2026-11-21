<?php

/*
|--------------------------------------------------------------------------
| Sitio: metadatos, navegación y footer
|--------------------------------------------------------------------------
|
| Los enlaces internos se definen por nombre de ruta ('route', 'params',
| 'fragment') para resolverlos con route() en las vistas. Los enlaces sin
| destino en el proyecto original se mantienen como '#'.
|
*/

return [

    'name' => 'Giunti Psychometrics Italy',

    'brand' => ['Giunti', 'Psychometrics'],

    'tagline' => 'Where Science Meets People',

    /** Tarjetas bajo el hero (cifras de ejemplo del original). */
    'hero_features' => [
        ['icon' => 'graduation-cap', 'title' => 'Oltre 100 corsi', 'description' => 'Formazione online e in presenza'],
        ['icon' => 'badge-check', 'title' => 'Certificazioni validate', 'description' => 'Riconosciute in ambito clinico e professionale'],
        ['icon' => 'globe', 'title' => 'Presenti in 15+ paesi', 'description' => 'Una rete internazionale di professionisti'],
        ['icon' => 'clipboard-list', 'title' => '500+ test psicometrici', 'description' => 'Strumenti validati scientificamente'],
    ],

    /** Test y Formazione van a /products con distinto ?type= */
    'navigation' => [
        ['label' => 'Test', 'route' => 'products.index', 'params' => ['type' => 'test']],
        ['label' => 'Formazione', 'route' => 'products.index', 'params' => ['type' => 'course']],
        ['label' => 'Risorse', 'route' => 'resources'],
        ['label' => 'Contatti', 'route' => 'home', 'fragment' => 'contatti'],
    ],

    'footer' => [
        'columns' => [
            [
                'title' => 'Area Clienti',
                'links' => [
                    ['label' => 'Aiuto', 'url' => '#'],
                    ['label' => 'Contattaci', 'route' => 'home', 'fragment' => 'contatti'],
                    ['label' => 'Condizioni generali di vendita', 'url' => '#'],
                    ['label' => 'Resi e Rimborsi', 'url' => '#'],
                    ['label' => 'Informativa Privacy', 'url' => '#'],
                    ['label' => 'Informativa Cookie', 'url' => '#'],
                    ['label' => 'Condizioni Findomestic', 'url' => '#'],
                ],
            ],
            [
                'title' => 'Giunti Psychometrics',
                'links' => [
                    ['label' => 'Chi siamo', 'url' => '#'],
                    ['label' => 'Divisione clinica sul territorio', 'url' => '#'],
                    ['label' => 'Lavora con noi', 'url' => '#'],
                    ['label' => 'Politica integrata parità di genere, ambiente e qualità', 'url' => '#'],
                    ['label' => 'Codice Etico', 'url' => '#'],
                    ['label' => 'Mod. 231', 'url' => '#'],
                    ['label' => 'Sistema di segnalazione', 'url' => '#'],
                ],
            ],
            [
                'title' => 'Esplora',
                'links' => [
                    ['label' => "Guida all'acquisto dei test", 'url' => '#'],
                    ['label' => 'Catalogo Test', 'url' => '#'],
                    ['label' => 'Autori', 'url' => '#'],
                    ['label' => 'Docenti', 'url' => '#'],
                    ['label' => 'Nesplora', 'url' => '#'],
                    ['label' => 'Notizie', 'url' => '#'],
                ],
            ],
            [
                'title' => 'Gruppo',
                'links' => [
                    ['label' => 'Giunti Psychometrics Italy', 'url' => '#'],
                    ['label' => 'Corporate', 'url' => '#'],
                ],
            ],
        ],

        'social' => ['linkedin' => 'LinkedIn', 'instagram' => 'Instagram', 'youtube' => 'YouTube', 'facebook' => 'Facebook'],

        'legal' => [
            'copyright' => 'Copyright © 2026 Giunti Psychometrics Italia S.r.l. | P.IVA 07367670481 · Giunti Psychometrics Italia S.r.l., tutti i diritti riservati',
            'dpo' => 'Responsabile della Protezione dei Dati (Art. 37 del REG UE 2016/679) Avv. Victoria Parise',
            'dpo_email' => 'privacy@giuntipsy.com',
        ],
    ],

];
