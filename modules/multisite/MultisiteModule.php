<?php
namespace yii\easyii\modules\multisite;

class MultisiteModule extends \yii\easyii\components\Module
{
    public $settings = [
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Multi Site',
            'ru' => 'Сайты',
        ],
        'icon' => 'globe',
        'order_num' => 130,
    ];
}