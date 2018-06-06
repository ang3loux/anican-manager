<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Person */

$this->title = 'Registrar paciente';
$this->params['breadcrumbs'][] = ['label' => 'Pacientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="person-create">

    <?= $this->render('_form', [
        'model' => $model,
        'modelRelationships' => $modelRelationships,
    ]) ?>

</div>
