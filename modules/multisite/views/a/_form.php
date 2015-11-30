<?php
use yii\easyii\widgets\DateTimePicker;
use yii\easyii\helpers\Image;
use yii\easyii\widgets\TagsInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\easyii\widgets\Redactor;
use yii\easyii\widgets\SeoForm;

$module = $this->context->module->id;
?>
<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
]); ?>

<?= $form->field($model, 'domain')->dropDownList(ArrayHelper::map(\yii\easyii\helpers\Multisite::getDomains(), 'domain', 'domain'), ['prompt' => Yii::t('easyii/multisite', 'Select site')]) ?>

<?= $form->field($model, 'domainCopy')->dropDownList(ArrayHelper::map(\yii\easyii\helpers\Multisite::getDomains(), 'domain', 'domain'), ['prompt' => Yii::t('easyii/multisite', 'Select site')]) ?>

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end(); ?>
