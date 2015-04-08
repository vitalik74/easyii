<?php
use dosamigos\datepicker\DatePicker;
use yii\easyii\helpers\Image;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\easyii\widgets\Redactor;
use yii\easyii\widgets\SeoForm;

$model->time = date('Y-m-d', $model->time);
?>
<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['enctype' => 'multipart/form-data']
]); ?>
<?= $form->field($model, 'title') ?>
<?php if($this->context->module->settings['enableThumb']) : ?>
    <?php if($model->image) : ?>
        <img src="<?= Image::thumb(Yii::getAlias('@webroot') . $model->image, 240, 180) ?>">
        <a href="/admin/news/a/clear-image/<?= $model->news_id ?>" class="text-danger confirm-delete" title="<?= Yii::t('easyii/news', 'Clear image')?>"><?= Yii::t('easyii/news', 'Clear image')?></a>
    <?php endif; ?>
    <?= $form->field($model, 'image')->fileInput() ?>
<?php endif; ?>
<?php if($this->context->module->settings['enableShort']) : ?>
    <?= $form->field($model, 'short')->textarea() ?>
<?php endif; ?>
<?= $form->field($model, 'text')->widget(Redactor::className(),[
    'options' => [
        'minHeight' => 400,
        'imageUpload' => '/admin/redactor/upload?dir=news',
        'fileUpload' => '/admin/redactor/upload?dir=news',
        'plugins' => ['fullscreen']
    ]
]) ?>

<?= $form->field($model, 'time')->widget(
    DatePicker::className(), [
        'language' => strtolower(substr(Yii::$app->language, 0, 2)),
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ]); ?>

<?php if(IS_ROOT) : ?>
    <?= $form->field($model, 'slug') ?>
    <?= SeoForm::widget(['model' => $model]) ?>
<?php endif; ?>

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>