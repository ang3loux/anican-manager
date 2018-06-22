<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PersonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Listado de Personas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="person-index">
    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a('Registrar persona', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout'=> "{items}\n{summary}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'role',
            'fullname',
            [
                'attribute' => 'sex',
                'value' => function ($model) {
                    return Yii::$app->params['sex'][$model->sex];
                },
                'filter' => Yii::$app->params['sex']
            ],
            //'birthdate',
            //'birthplace:ntext',
            'document',
            'email:email',
            'phone1',
            //'phone2',
            //'address:ntext',
            //'diagnosis:ntext',
            //'decease',
            //'deathdate',
            //'description:ntext',
            //'date',
            //'active',
            //'image',
            //'created_at',
            //'created_by',
            //'updated_at',
            //'updated_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
