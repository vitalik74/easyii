<?php
namespace yii\easyii\modules\multisite\controllers;

use Yii;
use yii\data\ActiveDataProvider;

use yii\data\ArrayDataProvider;
use yii\easyii\behaviors\StatusController;
use yii\easyii\components\Controller;
use yii\easyii\modules\multisite\models\DatabaseForm;
use yii\easyii\modules\multisite\models\Multisite;
use yii\widgets\ActiveForm;

class AController extends Controller
{
    public function init()
    {
        parent::init();
    }

    public function actionIndex()
    {
        $data = new ArrayDataProvider([
            'allModels' => Multisite::find(),
        ]);

        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionDatabase()
    {
        $model = new DatabaseForm();

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

                return ActiveForm::validate($model);
            } else {
                if ($model->save()) {
                    $this->flash('success', Yii::t('easyii/multisite', 'Structure database from {0} copy fo {1}', [$model->domain, $model->domainCopy]));
                } else {
                    $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
                }

                return $this->refresh();
            }
        } else {
            return $this->render('database', [
                'model' => $model
            ]);
        }
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        return $this->formatResponse(Yii::t('easyii/multisite', 'Entry deleted'));
    }

    public function actionOn($id)
    {
        $model = $this->findModel($id);

        return $model->changeStatus(Multisite::STATUS_ON);
    }

    public function actionOff($id)
    {
        $model = $this->findModel($id);

        return $model->changeStatus(Multisite::STATUS_OFF);
    }

    /**
     * @param $domain
     * @return Multisite|\yii\web\Response|static
     */
    protected function findModel($domain)
    {
        $model = Multisite::find($domain);

        if ($model === null) {
            $this->flash('error', Yii::t('easyii', 'Not found'));

            return $this->redirect(['/admin/' . $this->module->id]);
        }

        return $model;
    }
}