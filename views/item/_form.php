<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Item */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box box-primary">
    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>
        <div class="box-body">
            <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'description')->textArea(['rows' => '6']) ?>
            <?= $form->field($model, 'cooled')->radioList(Yii::$app->params['yesNo']); ?>
            <?= $form->field($model, 'unit')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'imageFile')->fileInput(['required' => empty($model->image), 'onChange'=>'readURL(this);']) ?>
            <div class="row center-col">
                <div class="col-md-6">
                    <img id="image" src=<?= !empty($model->image) ? '"' . $model->image . '"' : '""' ?> alt="" style="width: 300px;"/>
                </div>
            </div>
        </div>
        <div class="box-footer text-center">
            <?= Html::submitButton($model->isNewRecord ? 'Registrar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a('Cancelar', $model->isNewRecord ? ['index'] : ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>

<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $("#image").attr("src", e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>