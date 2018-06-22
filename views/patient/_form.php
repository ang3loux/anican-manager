<?php

use app\models\Person;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;
use yii\jui\DatePicker;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Person */
/* @var $form yii\widgets\ActiveForm */

$horizontalCssClasses = [
    'horizontalCssClasses' => [
        'label' => '',
        'offset' => '',
        'wrapper' => 'col-sm-12',
        'error' => '',
        'hint' => '',
    ]
];

$js = '
function setIndexNo() {
    $(".dynamicform_wrapper .item td:first-child b").each(function(index) {
        $(this).text(index + 1);
    });
}

$(".dynamicform_wrapper").on({
    afterInsert: function(e, item) {
        $(item).find("select").prop("disabled", false);
        setIndexNo();
    },
    afterDelete: function(e, item) {
        setIndexNo();
    }
});
';

$this->registerJs($js);
?>

<div class="box box-primary">
    <?php $form = ActiveForm::begin([
        'id' => 'dynamic-form',
        'layout' => 'horizontal',
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>
        <div class="box-body">
            <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'sex')->radioList(Yii::$app->params['sex']); ?>
            <?= $form->field($model, 'birthdate')->widget(DatePicker::className(), [
                'options' => ['class' => 'form-control'],
                'dateFormat' => 'yyyy-MM-dd'
            ]) ?>
            <?= $form->field($model, 'birthplace')->textArea(['rows' => '3']) ?>
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
            <?= $form->field($model, 'diagnosis')->textArea(['rows' => '3']) ?>
            <?= $form->field($model, 'decease')->radioList(Yii::$app->params['yesNo']); ?>
            <?= $form->field($model, 'deathdate')->widget(DatePicker::className(), [
                'options' => ['class' => 'form-control'],
                'dateFormat' => 'yyyy-MM-dd'
            ]) ?>
            <?= $form->field($model, 'description')->textArea(['rows' => '6']) ?>
            <?= $form->field($model, 'date')->widget(DatePicker::className(), [
                'options' => ['class' => 'form-control'],
                'dateFormat' => 'yyyy-MM-dd'
            ]) ?>
            <?= $form->field($model, 'active')->radioList(Yii::$app->params['active']); ?>
            <?= $form->field($model, 'imageFile')->fileInput(['required' => empty($model->image), 'onChange'=>'readURL(this);']) ?>
            <div class="row center-col">
                <div class="col-md-6">
                    <img id="image" src=<?= !empty($model->image) ? '"' . $model->image . '"' : '""' ?> alt="" style="width: 300px;"/>
                </div>
            </div>

            <hr>

            <?php
            DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items', // required: css class selector
                'widgetItem' => '.item', // required: css class
                'min' => 1, // 0 or 1 (default 1)
                'insertButton' => '.add-item', // css class
                'deleteButton' => '.remove-item', // css class
                'model' => $modelRelationships[0],
                'formId' => 'dynamic-form',
                'formFields' => [
                    'person_id',
                    'relationship',
                    'description',
                ],
            ]); ?>
                <table class="table table-striped table-bordered container-items">
                    <tr>
                        <th class="dynamic-5">#</th>
                        <th class="dynamic-30">Representante</th>
                        <th class="dynamic-20">Relación</th>
                        <th class="dynamic-45">Descripción</th>
                        <th>
                            <button type="button" class="pull-right add-item btn btn-success btn-xs"><i class="fa fa-plus"></i> Añadir</button>
                        </th>
                    </tr>
                    <?php foreach ($modelRelationships as $i => $modelRelationship): ?>
                        <tr class="item">
                            <td class="text-center">
                                <b><?= $i + 1 ?></b>
                                <?php if (!$modelRelationship->isNewRecord) {
                                    echo Html::activeHiddenInput($modelRelationship, "[{$i}]id");
                                } ?>
                            </td>
                            <td>
                                <?= $form->field($modelRelationship, "[{$i}]person_id", $horizontalCssClasses)->widget(Select2::className(), [
                                    'data' => ArrayHelper::map(Person::find()->where(['role' => 0])->all(), 'id', 'fullname')
                                ])->label(false) ?>
                            </td>
                            <td>
                                <?= $form->field($modelRelationship, "[{$i}]relationship", $horizontalCssClasses)->dropDownList(Yii::$app->params['relationships'])->label(false) ?>
                            </td>
                            <td>
                                <?= $form->field($modelRelationship, "[{$i}]description", $horizontalCssClasses)->textArea(['rows' => '6'])->label(false) ?>
                            </td>
                            <td>
                                <button type="button" class="pull-right remove-item btn btn-danger btn-xs">
                                    <i class="fa fa-minus"></i> Eliminar
                                </button>
                            </td>
                        </div>
                    <?php endforeach; ?>
                </table>
            <?php DynamicFormWidget::end(); ?>
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
