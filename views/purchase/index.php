<?php

use app\models\Person;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\select2\Select2;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PurchaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Listado de entradas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-index">
    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a('Registrar entrada', ['create'], ['class' => 'btn btn-success']) ?>
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
                    return Yii::$app->params['purchaseReasons'][$model->reason];
                },
                'filter' => Yii::$app->params['purchaseReasons']
            ],
            [
                'attribute' => 'person_id',
                'value' => function ($model) {
                    return $model->person->fullname;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'person_id',
                    'data' => ArrayHelper::map(Person::find()->where(['role' => [0, 2]])->all(), 'fullname', 'fullname'),
                    'options' => ['placeholder' => ''],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])
            ],
            [
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
