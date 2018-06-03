<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SaleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Listado de salidas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sale-index">
    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a('Registrar salida', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout'=> "{items}\n{summary}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'code',
            [
                'attribute' => 'reason',
                'value' => function ($model) {
                    switch ($model->reason) {
                        case 0:
                            return 'Donación';

                        case 1:
                            return 'Vencimiento';
                        
                        case 2:
                            return 'Otro';

                        default:
                            return 'Error';
                    }
                },
                'filter' => array('0' => 'Donación', '1' => 'Vencimiento', '2' => 'Otro')
            ],
            'customer',
            [
                // 'label' => 'Fecha de entrada',
                'attribute' => 'date',
                'value' => 'date',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'date',
                    'language' => 'es',
                    'dateFormat' => 'yyyy-MM-dd',
                    'options' => ['class' => 'datepicker-input']
                ]),
                'format' => 'html',
            ],
            //'created_at',
            //'created_by',
            //'updated_at',
            //'updated_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
