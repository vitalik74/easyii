<?php

namespace yii\easyii\modules\multisite\models;

use Yii;
use yii\easyii\behaviors\CacheFlush;
use yii\easyii\behaviors\SortableModel;
use yii\easyii\components\ActiveRecord;

class Multisite extends ActiveRecord
{
    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    const CACHE_KEY = 'easyii_multisite';

    public static function tableName()
    {
        return 'easyii_multisite';
    }

    public function rules()
    {
        return [
            [['domain', 'name'], 'required'],
            [['domain', 'name'], 'trim'],
            [['domain'], 'url', 'defaultScheme' => null, 'pattern' => '/^(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)/i'],
            [['name'], 'string', 'max' => 255],
            ['status', 'integer'],
            ['status', 'default', 'value' => self::STATUS_ON],
        ];
    }

    public function attributeLabels()
    {
        return [
            'domain' => Yii::t('easyii/multisite', 'Domain'),
            'name' => Yii::t('easyii/multisite', 'Name'),
            'status' => Yii::t('easyii/multisite', 'Status'),
        ];
    }

    public function behaviors()
    {
        return [
            CacheFlush::className()
        ];
    }
}