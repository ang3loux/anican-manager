<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Person */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box box-primary">
    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>
        <div class="box-body">
            <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'birthdate')->widget(DatePicker::className(), [
                'options' => ['class' => 'form-control'],
                'dateFormat' => 'yyyy-MM-dd'
            ]) ?>
            <?= $form->field($model, 'document')->widget(MaskedInput::className(), [
                'name' => 'document',
                'mask' => 'A-9{1,9}'
            ]) ?>
            <?= $form->field($model, 'email')->widget(MaskedInput::className(), [
                'name' => 'email',
                'clientOptions' => [
                    'alias' =>  'email'
                ],
            ]) ?>
            <?= $form->field($model, 'phone1')->widget(MaskedInput::className(), [
                'name' => 'phone1',
                'mask' => ['+9{1,9}-9{1,3}-9{1,9}']
            ]) ?>
            <?= $form->field($model, 'phone2')->widget(MaskedInput::className(), [
                'name' => 'phone2',
                'mask' => ['+9{1,9}-9{1,3}-9{1,9}']
            ]) ?>
            <?= $form->field($model, 'address')->textArea(['rows' => '3']) ?>
            <?= $form->field($model, 'description')->textArea(['rows' => '6']) ?>
            <?= $form->field($model, 'date')->widget(DatePicker::className(), [
                'options' => ['class' => 'form-control'],
                'dateFormat' => 'yyyy-MM-dd'
            ]) ?>
            <?= $form->field($model, 'imageFile')->fileInput(['required' => empty($model->image), 'onChange'=>'readURL(this);']) ?>
            <div class="row center-col">
                <div class="col-md-6">
                    <img id="image" src=<?= !empty($model->image) ? '"' . $model->image . '"' : '""' ?> alt="" style="width: 300px;"/>
                </div>
            </div>
        </div>
        <div class="box-footer text-center">
            <?= Html::submitButton($model->isNewRecord ? 'Registrar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-default']) ?>
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
