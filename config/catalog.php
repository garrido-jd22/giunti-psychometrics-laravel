<?php

/*
|--------------------------------------------------------------------------
| Catálogo de ejemplo
|--------------------------------------------------------------------------
|
| Datos de ejemplo con nombres genéricos (no corresponden a productos
| reales). Se leen a través de App\Contracts\ProductRepository, así que
| se podrán sustituir por una API sin tocar controladores ni vistas.
|
*/

return [

    'products' => [

        // Test

        [
            'slug' => 'test-personalita-adulti',
            'type' => 'test',
            'title' => 'Test di Personalità per Adulti',
            'description' => 'Questionario per descrivere i principali tratti di personalità nella vita quotidiana.',
            'author' => 'Team Clinico',
            'author_role' => 'Redazione scientifica',
            'category' => 'Clinica e Neuropsicologia',
            'format' => 'Digitale',
            'meta' => ['type' => 'age', 'label' => '18+ anni'],
            'price' => 320.0,
            'original_price' => 380.0,
            'image' => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&w=900&q=80',
            'action_type' => 'cart',
            'featured' => true,
        ],
        [
            'slug' => 'test-abilita-cognitive',
            'type' => 'test',
            'title' => 'Test delle Abilità Cognitive',
            'description' => 'Batteria di prove per valutare attenzione, memoria e ragionamento nei bambini.',
            'author' => 'Team Età Evolutiva',
            'author_role' => 'Redazione scientifica',
            'category' => 'Scuola ed Età evolutiva',
            'format' => 'Cartaceo',
            'meta' => ['type' => 'age', 'label' => '6 – 16 anni'],
            'price' => 540.0,
            'original_price' => 610.0,
            'image' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=900&q=80',
            'action_type' => 'cart',
            'featured' => true,
        ],
        [
            'slug' => 'scala-umore',
            'type' => 'test',
            'title' => "Scala dell'Umore",
            'description' => "Breve questionario di autovalutazione per misurare il tono dell'umore.",
            'author' => 'Team Clinico',
            'author_role' => 'Redazione scientifica',
            'category' => 'Clinica e Neuropsicologia',
            'format' => 'Cartaceo',
            'meta' => ['type' => 'age', 'label' => '14 – 80 anni'],
            'price' => 140.0,
            'image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=900&q=80',
            'action_type' => 'cart',
            'featured' => true,
        ],
        [
            'slug' => 'questionario-attenzione',
            'type' => 'test',
            'title' => "Questionario sull'Attenzione",
            'description' => 'Scale per genitori e insegnanti sui comportamenti di attenzione e iperattività.',
            'author' => 'Team Età Evolutiva',
            'author_role' => 'Redazione scientifica',
            'category' => 'Scuola ed Età evolutiva',
            'format' => 'Digitale',
            'meta' => ['type' => 'age', 'label' => '6 – 18 anni'],
            'price' => 210.0,
            'original_price' => 250.0,
            'image' => 'https://images.unsplash.com/photo-1588072432836-e10032774350?auto=format&fit=crop&w=900&q=80',
            'action_type' => 'cart',
        ],
        [
            'slug' => 'profilo-competenze',
            'type' => 'test',
            'title' => 'Profilo delle Competenze Professionali',
            'description' => 'Strumento per la selezione e lo sviluppo del personale in azienda.',
            'author' => 'Team Lavoro',
            'author_role' => 'Redazione scientifica',
            'category' => 'Lavoro e Organizzazioni',
            'format' => 'Digitale',
            'meta' => ['type' => 'age', 'label' => '18 – 70 anni'],
            'price' => 290.0,
            'original_price' => 340.0,
            'image' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=900&q=80',
            'action_type' => 'cart',
            'featured' => true,
        ],

        // Formazione

        [
            'slug' => 'corso-valutazione-clinica',
            'type' => 'course',
            'title' => 'Corso di Valutazione Clinica',
            'description' => 'Le basi per scegliere, somministrare e interpretare i test in ambito clinico.',
            'author' => 'Area Formazione',
            'author_role' => 'Docenti',
            'category' => 'Clinica e Neuropsicologia',
            'format' => 'Corso online',
            'meta' => ['type' => 'date', 'start_date' => '2026-12-10'],
            'price' => 260.0,
            'original_price' => 320.0,
            'image' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=900&q=80',
            'action_type' => 'details',
            'featured' => true,
        ],
        [
            'slug' => 'laboratorio-neuropsicologia',
            'type' => 'course',
            'title' => 'Laboratorio di Neuropsicologia',
            'description' => "Esercitazioni pratiche sulla valutazione delle funzioni cognitive nell'adulto.",
            'author' => 'Area Formazione',
            'author_role' => 'Docenti',
            'category' => 'Clinica e Neuropsicologia',
            'format' => 'In presenza',
            'meta' => ['type' => 'date', 'start_date' => '2027-01-15'],
            'price' => 420.0,
            'original_price' => 480.0,
            'image' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=900&q=80',
            'action_type' => 'details',
        ],
        [
            'slug' => 'corso-test-a-scuola',
            'type' => 'course',
            'title' => 'I Test a Scuola',
            'description' => 'Come usare gli strumenti di valutazione per supportare gli studenti.',
            'author' => 'Area Formazione',
            'author_role' => 'Docenti',
            'category' => 'Scuola ed Età evolutiva',
            'format' => 'Corso online',
            'meta' => ['type' => 'date', 'start_date' => '2027-02-03'],
            'price' => 180.0,
            'image' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=900&q=80',
            'action_type' => 'details',
            'featured' => true,
        ],
        [
            'slug' => 'workshop-selezione-personale',
            'type' => 'course',
            'title' => 'Workshop sulla Selezione del Personale',
            'description' => 'Metodi e strumenti per colloqui e valutazioni in contesto aziendale.',
            'author' => 'Area Formazione',
            'author_role' => 'Docenti',
            'category' => 'Lavoro e Organizzazioni',
            'format' => 'In presenza',
            'meta' => ['type' => 'date', 'start_date' => '2027-03-12'],
            'price' => 350.0,
            'original_price' => 400.0,
            'image' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=900&q=80',
            'action_type' => 'details',
        ],
        [
            'slug' => 'corso-sviluppo-infantile',
            'type' => 'course',
            'title' => 'Corso sullo Sviluppo Infantile',
            'description' => 'Le tappe dello sviluppo e come osservarle nella pratica professionale.',
            'author' => 'Area Formazione',
            'author_role' => 'Docenti',
            'category' => 'Scuola ed Età evolutiva',
            'format' => 'Corso online',
            'meta' => ['type' => 'date', 'start_date' => '2027-04-08'],
            'price' => 220.0,
            'image' => 'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?auto=format&fit=crop&w=900&q=80',
            'action_type' => 'details',
        ],

    ],

];
