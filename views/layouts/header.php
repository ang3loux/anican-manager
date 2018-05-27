<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a(
        '<span class="logo-mini">APP</span><span class="logo-lg"><b>anican</b>Manager</span>', 
        Yii::$app->homeUrl, 
        ['class' => 'logo']
    ) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">                

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-user-o" aria-hidden="true"></i>
                        <span class="hidden-xs"><?= Yii::$app->user->identity->username ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <i class="fa fa-user-circle-o" aria-hidden="true"></i>

                            <p>
                                <?= Yii::$app->user->identity->username ?>
                                <small>Miembro desde <?= date("F Y", Yii::$app->user->identity->created_at) ?></small>
                            </p>
                        </li>                        
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">                                
                                <?= Html::a(
                                    '<i class="fa fa-lock" aria-hidden="true"></i> Contraseña',
                                    ['/site/password'],
                                    ['class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                            <div class="pull-right">                                
                                <?= Html::a(
                                    '<i class="fa fa-sign-out" aria-hidden="true"></i> Cerrar sesión',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
