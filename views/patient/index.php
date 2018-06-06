<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PersonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Listado de Pacientes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="person-index">
    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a('Registrar paciente', ['create'], ['class' => 'btn btn-success']) ?>
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
                'attribute' => 'birthdate',
                'value' => function ($model) {
                    $from = new DateTime($model->birthdate);
                    $to   = new DateTime('today');
                    return $model->birthdate . ' / <b>' . $from->diff($to)->y . ' años</b>';
                },
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'date',
                    'language' => 'es',
                    'dateFormat' => 'yyyy-MM-dd',
                    'options' => ['class' => 'datepicker-input']
                ]),
                'format' => 'html',
            ],
            //'birthplace:ntext',
            [
                'attribute' => 'document',
                'value' => function ($model) {
                    return empty($model->document) ? '-' : $model->document;
                }
            ],
            'email:email',
            'phone1',
            //'phone2',
            //'address:ntext',
            'diagnosis:ntext',
            [
                'attribute' => 'decease',
                'value' => function ($model) {
                    return Yii::$app->params['yesNo'][$model->decease];
                },
                'filter' => Yii::$app->params['yesNo']
            ],
            //'deathdate',
            //'description:ntext',
            //'date',
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
