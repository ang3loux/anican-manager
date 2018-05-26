<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Usuario';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Cambiar Contraseña</h3>
    </div>
    <?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>
        <div class="box-body">
            <?= $form->field($model, 'oldPassword')->passwordInput()->label('Contraseña actual') ?>
            <?= $form->field($model, 'newPassword')->passwordInput()->label('Contraseña nueva') ?>
        </div>
        <div class="box-footer text-center">
            <?= Html::submitButton('Actualizar', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
