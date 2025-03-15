<?php

return [
    'documents' => [
        'loading-note' => [
            'document_name' => 'Nota de Carga',
            'view_path' => 'pdf.v2.orders.loading_note',
            'subject_template' => 'Nota de Carga - Pedido #{order_id}',
            'body_template' => 'emails.orders.generic', // ✅ Cambiado a genérico
        ],
        'packing-list' => [
            'document_name' => 'Packing List',
            'view_path' => 'pdf.v2.orders.order_packing_list',
            'subject_template' => 'Packing List - Pedido #{order_id}',
            'body_template' => 'emails.orders.generic', // ✅ Cambiado a genérico
        ],
        'CMR' => [
            'document_name' => 'Documento de Transporte (CMR)',
            'view_path' => 'pdf.v2.orders.CMR',
            'subject_template' => 'Documento de Transporte (CMR) - Pedido #{order_id}',
            'body_template' => 'emails.orders.generic', // ✅ Cambiado a genérico
        ],
    ],

    'standard_recipients' => [
        'cliente' => ['nota_carga', 'packing_list'],
        'comercial' => ['nota_carga', 'packing_list'],
        'transporte' => ['cmr', 'packing_list'],
    ],

];
