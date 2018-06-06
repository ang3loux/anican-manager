<aside class="main-sidebar">

    <section class="sidebar">

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],                
                'items' => [
                    ['label' => 'Menu', 'options' => ['class' => 'header']],
                    ['label' => 'Inicio', 'icon' => 'home', 'url' => ['/']],
                    [
                        'label' => 'Personas',
                        'icon' => 'users',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Personas', 'icon' => 'list', 'url' => ['/person'],],
                            ['label' => 'Pacientes', 'icon' => 'list', 'url' => ['/patient'],],
                            ['label' => 'Registrar persona', 'icon' => 'plus', 'url' => ['/person/create'],],
                            ['label' => 'Registrar paciente', 'icon' => 'plus', 'url' => ['/patient/create'],],
                        ],
                    ],
                    [
                        'label' => 'Items',
                        'icon' => 'shopping-basket',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Items', 'icon' => 'list', 'url' => ['/item'],],
                            ['label' => 'Registrar item', 'icon' => 'plus', 'url' => ['/item/create'],],
                        ],
                    ],
                    [
                        'label' => 'Entradas',
                        'icon' => 'download',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Entradas', 'icon' => 'list', 'url' => ['/purchase'],],
                            ['label' => 'Registrar entrada', 'icon' => 'plus', 'url' => ['/purchase/create'],],
                        ],
                    ],
                    [
                        'label' => 'Salidas',
                        'icon' => 'upload',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Salidas', 'icon' => 'list', 'url' => ['/sale'],],
                            ['label' => 'Registrar salida', 'icon' => 'plus', 'url' => ['/sale/create'],],
                        ],
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
