<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Sale */

$this->title = 'Salida: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Salidas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sale-view">
    <div class="box box-primary">
        <div class="box-body">
            <div class="row center-col">
                <div class="col-md-8">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'code',
                            [
                                'attribute' => 'reason',
                                'value' => $model->reason == 0 ? 'Donación' : 'Otro',
                            ],
                            'customer',
                            'date',
                            // 'created_at',
                            // 'created_by',
                            // 'updated_at',
                            // 'updated_by',
                        ],
                    ]) ?>                
                </div>
            </div>

            <hr>
            <h2><i class="fa fa-shopping-basket" aria-hidden="true"></i> Items eliminados:</h2>

            <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'layout'=> "{items}\n{summary}\n{pager}",
                'columns' => [
                    //'id',
                    // 'sale_id',
                    [
                        'attribute' => 'item_id',
                        'value' => 'item.name'
                    ],
                    'quantity',
                    // 'price',
                    //'created_at',
                    //'created_by',
                    //'updated_at',
                    //'updated_by',
                    [
                        'class'    => 'yii\grid\ActionColumn',
                        'template' => '{view}',
                        'buttons'  => [
                            'view'   => function ($url, $modelSaleDetail) {
                                $url = Url::to(['item/view', 'id' => $modelSaleDetail->item->id]);
                                return Html::a('<span class="fa fa-eye"></span>', $url, [
                                    'title' => 'view',
                                    'data-pjax' => '0',
                                ]);
                            },
                        ]
                    ]
                ],
            ]); ?>
            <?php Pjax::end(); ?>
        </div>

        </br>

        <div class="box-footer text-center">
            <p>
                <?= Html::a('Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => '¿Está seguro de eliminar este registro?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
        </div>
    </div>
</div>
