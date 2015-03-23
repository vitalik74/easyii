<?php
use yii\easyii\modules\article\models\Category;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\easyii\widgets\SeoForm;

$settings = $this->context->module->settings;
?>
<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['enctype' => 'multipart/form-data']
]); ?>
<?= $form->field($model, 'title') ?>

<?php if(!empty($parent)) : ?>
    <div class="form-group field-category-title required">
        <label for="category-parent" class="control-label"><?= Yii::t('easyii/article', 'Parent category') ?></label>
        <select class="form-control" id="category-parent" name="parent">
            <option value="" class="smooth"><?= Yii::t('easyii', 'No') ?></option>
            <?php foreach(Category::find()->sort()->asArray()->all() as $node) : ?>
                <option
                    value="<?= $node['category_id'] ?>"
                    <?php if($parent == $node['category_id']) echo 'SELECTED' ?>
                    style="padding-left: <?= $node['depth']*20 ?>px;"
                ><?= $node['title'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
<?php endif; ?>

<?php if($settings['categoryThumb']) : ?>
    <?php if($model->thumb) : ?>
        <img src="<?= Yii::$app->request->baseUrl.$model->thumb ?>">
        <a href="/admin/article/a/clear-image/<?= $model->primaryKey ?>" class="text-danger confirm-delete" title="<?= Yii::t('easyii/article', 'Clear image')?>"><?= Yii::t('easyii/article', 'Clear image')?></a>
    <?php endif; ?>
    <?= $form->field($model, 'thumb')->fileInput() ?>
<?php endif; ?>

<?php if(IS_ROOT) : ?>
    <?= $form->field($model, 'slug') ?>
    <?= SeoForm::widget(['model' => $model]) ?>
<?php endif; ?>

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>