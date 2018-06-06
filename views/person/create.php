<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Person */

$this->title = 'Registrar persona';
$this->params['breadcrumbs'][] = ['label' => 'Personas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="person-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
