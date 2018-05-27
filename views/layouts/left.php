<aside class="main-sidebar">

    <section class="sidebar">

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],                
                'items' => [
                    ['label' => 'Menu', 'options' => ['class' => 'header']],
                    ['label' => 'Inicio', 'icon' => 'home', 'url' => ['/']],
                    [
                        'label' => 'Items',
                        'icon' => 'shopping-basket',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Lista de items', 'icon' => 'list', 'url' => ['/item'],],
                            ['label' => 'Crear item', 'icon' => 'plus', 'url' => ['/item/create'],],
                        ],
                    ],
                    [
                        'label' => 'Entradas',
                        'icon' => 'download',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Lista de entradas', 'icon' => 'list', 'url' => ['/purchase'],],
                            ['label' => 'Registrar entrada', 'icon' => 'plus', 'url' => ['/purchase/create'],],
                        ],
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
