<?php

use app\models\Person;
use app\models\Item;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Purchase */
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
    <?php $form = ActiveForm::begin(['id' => 'dynamic-form', 'layout' => 'horizontal']); ?>
        <div class="box-body">
            <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'reason')->radioList(Yii::$app->params['purchaseReasons']); ?>
            <?= $form->field($model, "person_id")->widget(Select2::className(), [
                'data' => ArrayHelper::map(Person::find()->where(['role' => [0, 2]])->all(), 'id', 'fullname')
            ]) ?>
            <?= $form->field($model, 'date')->widget(DatePicker::className(), [
                'options' => ['class' => 'form-control'],
                'dateFormat' => 'yyyy-MM-dd'
            ]) ?>
            <?php
            DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items', // required: css class selector
                'widgetItem' => '.item', // required: css class
                'min' => 1, // 0 or 1 (default 1)
                'insertButton' => '.add-item', // css class
                'deleteButton' => '.remove-item', // css class
                'model' => $modelDetails[0],
                'formId' => 'dynamic-form',
                'formFields' => [
                    'item_id',
                    'expiration',
                    'quantity',
                    'price',
                    'currency',
                    'description'
                ],
            ]); ?>
                <table class="table table-striped table-bordered container-items">
                    <tr>
                        <th class="dynamic-5">#</th>
                        <th class="dynamic-20">Item</th>
                        <th class="dynamic-10">Fecha de vencimiento</th>
                        <th class="dynamic-15">Cantidad</th>
                        <th class="dynamic-15">Precio</th>
                        <th class="dynamic-10">Moneda</th>
                        <th class="dynamic-25">Descripción</th>
                        <th>
                            <button type="button" class="pull-right add-item btn btn-success btn-xs"><i class="fa fa-plus"></i> Añadir</button>
                        </th>
                    </tr>
                    <?php foreach ($modelDetails as $i => $modelDetail): ?>
                        <tr class="item">
                            <td class="text-center">
                                <b><?= $i + 1 ?></b>
                                <?php if (!$modelDetail->isNewRecord) {
                                    echo Html::activeHiddenInput($modelDetail, "[{$i}]id");
                                } ?>
                            </td>
                            <td>
                                <?= $form->field($modelDetail, "[{$i}]item_id", $horizontalCssClasses)->widget(Select2::className(), [
                                    'data' => ArrayHelper::map(Item::find()->all(), 'id', 'name')
                                ])->label(false) ?>
                            </td>
                            <td>
                                <?= $form->field($modelDetail, "[{$i}]expiration", $horizontalCssClasses)->widget(DatePicker::className(), [
                                    'options' => ['class' => 'form-control'],
                                    'dateFormat' => 'yyyy-MM-dd'
                                ])->label(false) ?>
                            </td>
                            <td>
                                <?= $form->field($modelDetail, "[{$i}]quantity", $horizontalCssClasses)->textInput()->label(false) ?>
                            </td>
                            <td>
                                <?= $form->field($modelDetail, "[{$i}]price", $horizontalCssClasses)->textInput()->label(false) ?>
                            </td>
                            <td>
                                <?= $form->field($modelDetail, "[{$i}]currency", $horizontalCssClasses)->dropDownList(Yii::$app->params['currencies'])->label(false) ?>
                            </td>
                            <td>
                                <?= $form->field($modelDetail, "[{$i}]description", $horizontalCssClasses)->textArea(['rows' => '6'])->label(false) ?>
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
