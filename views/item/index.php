<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Listado de Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-index">
    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a('Crear item', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout'=> "{items}\n{summary}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'code',
            'name',
            //'description',
            [
                'attribute' => 'cooled',
                'value' => function ($model) {
                    return $model->cooled == 0 ? 'No' : 'Si';
                },
                'filter' => array('0' => 'No', '1' => 'Si')
            ],
            //'unit',
            'stock',
            //'created_at',
            //'created_by',
            //'updated_at',
            //'updated_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
