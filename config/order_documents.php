<?php

return [
    'documents' => [
        'nota_carga' => [
            'mailable' => \App\Mail\GenericOrderDocument::class,
            'default_recipients' => ['cliente', 'comercial'],
            'subject_template' => 'Nota de Carga - Pedido #{order_id}',
            'body_template' => 'emails.orders.nota_carga',
        ],
        'packing_list' => [
            'mailable' => \App\Mail\GenericOrderDocument::class,
            'default_recipients' => ['cliente', 'comercial'],
            'subject_template' => 'Packing List - Pedido #{order_id}',
            'body_template' => 'emails.orders.packing_list',
        ],
        'cmr' => [
            'mailable' => \App\Mail\GenericOrderDocument::class,
            'default_recipients' => ['transporte'],
            'subject_template' => 'Documento de Transporte (CMR) - Pedido #{order_id}',
            'body_template' => 'emails.orders.cmr',
        ],
    ],

    'standard_recipients' => [
        'cliente' => ['nota_carga', 'packing_list'],
        'comercial' => ['nota_carga', 'packing_list'],
        'transporte' => ['cmr', 'packing_list'],
    ],


    'recipients' => [
       
    ],
];
