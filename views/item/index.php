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
        <?= Html::a('Registrar item', ['create'], ['class' => 'btn btn-success']) ?>
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
                    return Yii::$app->params['yesNo'][$model->cooled];
                },
                'filter' => Yii::$app->params['yesNo']
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
