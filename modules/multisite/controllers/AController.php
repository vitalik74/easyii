<?php
namespace yii\easyii\modules\multisite\controllers;

use Yii;
use yii\data\ActiveDataProvider;

use yii\easyii\behaviors\StatusController;
use yii\easyii\components\Controller;
use yii\easyii\modules\multisite\models\Multisite;
use yii\widgets\ActiveForm;

class AController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => StatusController::className(),
                'model' => Multisite::className()
            ]
        ];
    }

    public function init()
    {
        parent::init();
    }

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Multisite::find()->desc(),
        ]);

        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionCreate()
    {
        $model = new Multisite();

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

                return ActiveForm::validate($model);
            } else {
                if ($model->save()) {
                    $this->flash('success', Yii::t('easyii/multisite', 'Site created'));

                    return $this->redirect(['/admin/' . $this->module->id]);
                } else {
                    $this->flash('error', Yii::t('easyii', 'Create error. {0}', $model->formatErrors()));

                    return $this->refresh();
                }
            }
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

                return ActiveForm::validate($model);
            } else {
                if ($model->save()) {
                    $this->flash('success', Yii::t('easyii/news', 'News updated'));
                } else {
                    $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
                }

                return $this->refresh();
            }
        } else {
            return $this->render('edit', [
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
        return $this->changeStatus($id, Multisite::STATUS_ON);
    }

    public function actionOff($id)
    {
        $models = Multisite::findAll(['status' => Multisite::STATUS_ON, ['not in', 'id', [$id]]]);

        if (count($models) <= 1) {
            $this->flash('error', Yii::t('easyii/multisite', 'Last site not change to off'));

            return $this->refresh();
        }

        return $this->changeStatus($id, Multisite::STATUS_OFF);
    }

    /**
     * @param $id
     * @return Multisite|\yii\web\Response|static
     */
    protected function findModel($id)
    {
        $model = Multisite::findOne($id);

        if ($model === null) {
            $this->flash('error', Yii::t('easyii', 'Not found'));

            return $this->redirect(['/admin/' . $this->module->id]);
        }

        return $model;
    }
}