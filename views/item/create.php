<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Item */

$this->title = 'Crear nuevo item';
$this->params['breadcrumbs'][] = ['label' => 'Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
