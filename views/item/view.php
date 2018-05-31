<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Item */

$this->title = 'Item: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-view">
    <div class="box box-primary">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'code',
                    'name',
                    'unit',
                    'quantity',
                    'stock',
                    'price',
                    // 'created_at',
                    // 'created_by',
                    // 'updated_at',
                    // 'updated_by',
                ],
            ]) ?>

            <hr>

            <?php Pjax::begin(); ?>
            <div class="row">
                <div class="col-md-6">
                    <h2><i class="fa fa-download" aria-hidden="true"></i> Entradas:</h2>
                    <?= GridView::widget([
                        'dataProvider' => $dataProviderPurchase,
                        'filterModel' => $searchModelPurchase,
                        'layout'=> "{items}\n{summary}\n{pager}",
                        'columns' => [
                            //'id',
                            [
                                'label' => 'Fecha de entrada',
                                'attribute' => 'purchase_id',
                                'value' => 'purchase.date',
                                'filter' => DatePicker::widget([
                                    'model' => $searchModelPurchase,
                                    'attribute' => 'purchase_id',
                                    'language' => 'es',
                                    'dateFormat' => 'yyyy-MM-dd',
                                    'options' => ['class' => 'datepicker-input']
                                ]),
                                'format' => 'html',
                                // 'filter' => DatePicker::widget(['language' => 'es', 'dateFormat' => 'dd-MM-yyyy']),
                                // 'format' => 'date',
                            ],
                            //'item_id',
                            'quantity',
                            //'price',
                            //'created_at',
                            //'created_by',
                            //'updated_at',
                            //'updated_by',
                        ],
                    ]); ?>
                </div>
                <div class="col-md-6">
                    <h2><i class="fa fa-upload" aria-hidden="true"></i> Salidas:</h2>
                    <?= GridView::widget([
                        'dataProvider' => $dataProviderSale,
                        'filterModel' => $searchModelSale,
                        'layout'=> "{items}\n{summary}\n{pager}",
                        'columns' => [
                            //'id',
                            [
                                'label' => 'Fecha de salida',
                                'attribute' => 'sale_id',
                                'value' => 'sale.date',
                                'filter' => DatePicker::widget([
                                    'model' => $searchModelSale,
                                    'attribute' => 'sale_id',
                                    'language' => 'es',
                                    'dateFormat' => 'yyyy-MM-dd',
                                    'options' => ['class' => 'datepicker-input']
                                ]),
                                'format' => 'html',
                                // 'filter' => DatePicker::widget(['language' => 'es', 'dateFormat' => 'dd-MM-yyyy']),
                                // 'format' => 'date',
                            ],
                            //'item_id',
                            'quantity',
                            //'price',
                            //'created_at',
                            //'created_by',
                            //'updated_at',
                            //'updated_by',
                        ],
                    ]); ?>
                </div>
            </div>
            <?php Pjax::end(); ?>
        </div>
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
