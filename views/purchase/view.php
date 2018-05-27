<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Purchase */

$this->title = 'Entrada: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Entradas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-view">
    <div class="box box-primary">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'code',
                    'supplier',
                    'date',
                    'total',
                    // 'created_at',
                    // 'created_by',
                    // 'updated_at',
                    // 'updated_by',
                ],
            ]) ?>

            <hr>
            <h2><i class="fa fa-shopping-basket" aria-hidden="true"></i> Items añadidos:</h2>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'layout'=> "{items}\n{summary}\n{pager}",
                'columns' => [
                    //'id',
                    // 'purchase_id',
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
                ],
            ]); ?>
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