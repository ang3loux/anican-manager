<?php
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'anicanManager';
?>
<div class="site-index">
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <a href="<?= Url::toRoute('/item') ?>">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>Items</h3>
                        <p>Listado de items</p>
                    </div>
                    <div class="icon">
                        <i class="glyphicon glyphicon-gift"></i>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-xs-6">
            <a href="<?= Url::toRoute('/purchase') ?>">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>Entradas</h3>
                        <p>Listado de entradas</p>
                    </div>
                    <div class="icon">
                        <i class="glyphicon glyphicon-import"></i>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-xs-6">
            <a href="<?= Url::toRoute('/sale') ?>">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>Salidas</h3>
                        <p>Listado de salidas</p>
                    </div>
                    <div class="icon">
                        <i class="glyphicon glyphicon-export"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
