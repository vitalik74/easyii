<?php
use yii\easyii\modules\multisite\models\Multisite;
use yii\easyii\modules\news\models\News;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('easyii/multisite', 'Multisite');

$module = $this->context->module->id;
?>

<?= $this->render('_menu') ?>

<?php if ($data->count > 0 && IS_ROOT) : ?>
    <table class="table table-hover">
        <thead>
        <tr>
            <th width="120"><?= Yii::t('easyii/multisite', 'Domain') ?></th>
            <th width="100"><?= Yii::t('easyii', 'Status') ?></th>
            <th width="120"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data->models as $key => $item) : ?>
            <tr data-id="<?= $key ?>">
                <td><?= $item['domain'] ?></td>
                <td class="status">
                    <?php
                    if ($item['domain'] != Multisite::DEFAULT_DOMAIN) {
                    ?>
                        <?= Html::checkbox('', $item['status'] == Multisite::STATUS_ON, [
                            'class' => 'switch',
                            'data-id' => crc32($item['domain']),
                            'data-link' => Url::to(['/admin/' . $module . '/a']),
                        ]) ?>
                    <?php
                    }
                    ?>
                </td>
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                    <?php
                    if ($item['domain'] != Multisite::DEFAULT_DOMAIN) {
                    ?>
                        <a href="<?= Url::to(['/admin/' . $module . '/a/delete', 'id' => crc32($item['domain'])]) ?>"
                           class="btn btn-default confirm-delete" title="<?= Yii::t('easyii', 'Delete item') ?>"><span
                                class="glyphicon glyphicon-remove"></span></a>
                    <?php
                    }
                    ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?= yii\widgets\LinkPager::widget([
        'pagination' => $data->pagination
    ]) ?>
<?php else : ?>
    <p><?= Yii::t('easyii', 'No records found') ?></p>
<?php endif; ?>