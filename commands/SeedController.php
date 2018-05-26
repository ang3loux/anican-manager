<?php
namespace app\commands;

use yii\console\Controller;
use app\models\User;

class SeedController extends Controller
{
    public function actionUser($email, $username, $password)
    {
        $password_hash = \Yii::$app->getSecurity()->generatePasswordHash($password);
        $user = new User();
        $user->username = $username;
        $user->password_hash = $password_hash;
        $user->auth_key = 'secret';
        $user->email = $email;
        $user->status = '10';
        $user->created_at = '';
        $user->updated_at = '';
        return $user->save();
    }
}