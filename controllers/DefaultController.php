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
        Yii::$app->session->set(Multisite::SESSION_DOMAIN_KEY, strtolower($domain));

        return $this->redirect(['index']);
    }
}