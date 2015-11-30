<?php

$this->title = Yii::t('easyii/multisite', 'Multisite');

$module = $this->context->module->id;
?>

<?= $this->render('_menu') ?>

<?= $this->render('_form', ['model' => $model]) ?>


