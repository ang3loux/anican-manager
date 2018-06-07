<?php

return [
    'adminEmail' => 'admin@example.com',
    'personRoles' => [
        0 => 'Persona',
        1 => 'Paciente',
        2 => 'Anónimo'
    ],
    'relationships' => [
        0 => 'Padre',
        1 => 'Madre',
        2 => 'Hermano/a',
        3 => 'Tío/a',
        4 => 'Primo/a',
        5 => 'Abuelo/a',
        6 => 'Amigo/a',
        7 => 'Otro',
    ],
    'patientRelationships' => [
        0 => 'Hijo/a',
        1 => 'Hijo/a',
        2 => 'Hermano/a',
        3 => 'Sobrino/a',
        4 => 'Primo/a',
        5 => 'Nieto/a',
        6 => 'Amigo/a',
        7 => 'Otro',
    ],
    'yesNo' => [
        0 => 'No',
        1 => 'Si'
    ],
    'purchaseReasons' => [
        0 => 'Compra',
        1 => 'Donación',
        2 => 'Otro'
    ],
    'saleReasons' => [
        0 => 'Donación',
        1 => 'Vencimiento',
        2 => 'Otro'
    ],
    'currencies' => [
        'VEF' => 'VEF',
        'COP' => 'COP',
        'USD' => 'USD'
    ],
    'uploadPath' => [
        'persons' => 'uploads/person-images/',
        'items' => 'uploads/item-images/'
    ]
];
