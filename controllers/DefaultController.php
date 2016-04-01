<?php
namespace yii\easyii\controllers;

use Yii;
use yii\easyii\modules\multisite\models\Multisite;

class DefaultController extends \yii\easyii\components\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionChangeSite($domain)
    {
        Yii::$app->cache->flush();
        setcookie(Multisite::COOKIE_DOMAIN_KEY, strtolower($domain), time() + 24 * 3600, '/');

        return $this->redirect(['index']);
    }
}