<?php

use app\models\Item;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Sale */
/* @var $form yii\widgets\ActiveForm */

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
            <?= $form->field($model, 'reason')->radioList(['0' => 'Donación', '1' => 'Vencimiento', '2' => 'Otro']); ?>
            <?= $form->field($model, 'customer')->textInput(['maxlength' => true]) ?>
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
                    'quantity',
                    'price',
                ],
            ]); ?>
                <table class="table table-striped table-bordered container-items">
                    <tr>
                        <th class="dynamic-number">#</th>
                        <th class="dynamic-item">Item</th>
                        <th>Cantidad</th>
                        <th class="dynamic-actions">
                            <button type="button" class="pull-right add-item btn btn-success btn-xs"><i class="fa fa-plus"></i> Añadir</button>
                        </th>
                    </tr>
                    <?php foreach ($modelDetails as $i => $modelDetail): ?>
                        <tr class="item">
                            <td>
                                <b><?= $i + 1 ?></b>
                                <?php if (!$modelDetail->isNewRecord) {
                                    echo Html::activeHiddenInput($modelDetail, "[{$i}]id");
                                } ?>
                            </td>
                            <td><?= $form->field($modelDetail, "[{$i}]item_id")->widget(Select2::className(), [
                                'data' => ArrayHelper::map(Item::find()->all(), 'id', 'name'),
                                'disabled' => !$modelDetail->isNewRecord
                            ])->label(false) ?></td>
                            <td><?= $form->field($modelDetail, "[{$i}]quantity")->textInput()->label(false) ?></td>
                            <td><button type="button" class="pull-right remove-item btn btn-danger btn-xs"><i class="fa fa-minus"></i> Eliminar</button></td>
                        </div>
                    <?php endforeach; ?>
                </table>
            <?php DynamicFormWidget::end(); ?>
        </div>
        <div class="box-footer text-center">
            <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
