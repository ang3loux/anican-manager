<?php
namespace app\components;
use Yii;

class AccessControl extends \yii\base\ActionFilter
{
    public function beforeAction($action)
    {
        $user = \Yii::$app->user;
        if ($action->id !== 'login' && $user->getIsGuest()) {
            $user->loginRequired();
        }
        return true;
    }
}
