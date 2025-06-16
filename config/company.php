<?php
/* Constantes de configuración de la empresa */


return [
    'name' => 'Congelados Brisamar S.L.',
    'cif' => 'B21573282',
    'address' => [
        'street' => 'C/Dieciocho de Julio de 1922 Nº2',
        'city' => 'Isla Cristina',
        'postal_code' => '21410',
        'province' => 'Huelva',
        'country' => 'España',
    ],

    // Información legal
    'legal' => [
        'terms_url' => '/docs/condiciones-legales.pdf',
        'privacy_policy_url' => '/docs/politica-privacidad.pdf',
    ],

    'bcc_email' => 'pedidos@congeladosbrisamar.es', // Correo electrónico para copias ocultas (BCC) en envíos de correos electronicos a traves de la API

    'contact' => [
        // Correo electrónico de contacto para operaciones
        // Aparece como contacto en la web y en los correos electrónicos
        'email_operations' => 'jose@congeladosbrisamar.es',
        // Aparece como contacto en la web y en los correos electrónicos relativos a pedidos
        'email_orders' => 'pedidos@congeladosbrisamar.es',
        // Aparece como contacto en la web y en los correos electrónicos relativos a pedidos o incidencias

        'phone_orders' => '+34 613 091 494',
        'emergency_email' => 'emergencias@congeladosbrisamar.es',
        'incidents_email' => 'incidencias@congeladosbrisamar.es',
        'loading_email' => 'carga@congeladosbrisamar.es',
        'unloading_email' => 'descarga@congeladosbrisamar.es',
    ],

    'loading_place' => 'Isla Cristina - Huelva',
    'signature_location' => 'Isla Cristina',
    'sanitary_number' => 'ES 12.021462/H CE', // Nº de registro sanitario

    'website_url' => 'https://congeladosbrisamar.es',
    'logo_url_small' => 'https://congeladosbrisamar.es/logos/logo-brisamar-small.png',


];
