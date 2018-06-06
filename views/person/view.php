<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Person */

$this->title = 'Persona: ' . $model->fullname;
$this->params['breadcrumbs'][] = ['label' => 'Personas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="person-view">
    <div class="box box-primary">
        <div class="box-body">
            <div class="row center-col">
                <div class="col-md-6">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            [
                                'attribute' => 'role',
                                'value' => Yii::$app->params['personRoles'][$model->role]
                            ],
                            'fullname',
                            'birthdate',
                            //'birthplace:ntext',
                            'document',
                            'email:email',
                            'phone1',
                            [
                                'attribute' => 'phone2',
                                'value' => empty($model->phone2) ? '-' : $model->phone2
                            ],
                            'address:ntext',
                            //'diagnosis:ntext',
                            //'decease',
                            //'deathdate',
                            [
                                'attribute' => 'description',
                                'value' => empty($model->description) ? '-' : $model->description,
                                'format' => 'ntext'
                            ],
                            'date',
                            //'created_at',
                            //'created_by',
                            //'updated_at',
                            //'updated_by',
                        ],
                    ]) ?>
                </div>
                <div class="col-md-4 center-col">
                    <a href= <?= $model->image ?> target="_blank">
	                	<img src=<?=$model->image?> style="height: 260px;">
	            	</a>
                </div>
            </div>

            <hr>

            <?php Pjax::begin(); ?>
            <div class="row">
                <div class="col-md-6">
                    <h2><i class="fa fa-users" aria-hidden="true"></i> Relaciones:</h2>
                    <?= GridView::widget([
                        'dataProvider' => $dataProviderRelationship,
                        'filterModel' => $searchModelRelationship,
                        'layout'=> "{items}\n{summary}\n{pager}",
                        'columns' => [
                            //'id',
                            [
                                'attribute' => 'patient_id',
                                'value' => 'patient.fullname',
                            ],
                            //person_id,
                            [
                                'attribute' => 'relationship',
                                'value' => function ($model) {
                                    return Yii::$app->params['patientRelationships'][$model->relationship];
                                },
                                'filter' => Yii::$app->params['patientRelationships']
                            ],
                            [
                                'attribute' => 'description',
                                'value' => function ($model) {
                                    return empty($model->description) ? '-' : $model->description;
                                },
                                'format' => 'ntext'
                            ],
                            [
                                'class'    => 'yii\grid\ActionColumn',
                                'template' => '{view}',
                                'buttons'  => [
                                    'view'   => function ($url, $modelRelationship) {
                                        $url = Url::to(['patient/view', 'id' => $modelRelationship->patient->id]);
                                        return Html::a('<span class="fa fa-eye"></span>', $url, [
                                            'title' => 'view',
                                            'data-pjax' => '0',
                                        ]);
                                    },
                                ]
                            ]
                        ],
                    ]); ?>
                </div>
                <!-- Purchase Gridview -->
            </div>
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
