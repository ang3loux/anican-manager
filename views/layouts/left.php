<aside class="main-sidebar">

    <section class="sidebar">

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],                
                'items' => [
                    ['label' => 'Menu', 'options' => ['class' => 'header']],
                    ['label' => 'Inicio', 'icon' => 'home', 'url' => ['/']],
                    [
                        'label' => 'Item',
                        'icon' => 'shopping-basket',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Lista de items', 'icon' => 'list', 'url' => ['/item'],],
                            ['label' => 'Crear item', 'icon' => 'plus', 'url' => ['/item/create'],],
                        ],
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
