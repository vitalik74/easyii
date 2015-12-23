<?php

namespace yii\easyii\modules\multisite\models;

use Yii;
use yii\easyii\components\Model;
use yii\helpers\ArrayHelper;

class Multisite extends Model
{
    const STATUS_OFF = 'disabled-';
    const STATUS_ON = '';

    const CACHE_KEY = 'easyii_multisite';

    const DEFAULT_DOMAIN = 'default';

    const COOKIE_DOMAIN_KEY = 'easyii_domain';

    public $domain;
    public $status;

    public function rules()
    {
        return [
            [['domain', 'name'], 'required'],
            [['domain', 'name'], 'trim'],
            [['domain'], 'url', 'defaultScheme' => null, 'pattern' => '/^(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)/i'],
            ['status', 'integer'],
            ['status', 'default', 'value' => self::STATUS_ON],
        ];
    }

    public function attributeLabels()
    {
        return [
            'domain' => Yii::t('easyii/multisite', 'Domain'),
            'status' => Yii::t('easyii/multisite', 'Status')
        ];
    }

    public function save()
    {
        if ($this->validate()) {

        }

        return false;
    }

    public function changeStatus($status)
    {
        if ($status == static::STATUS_OFF) {
            return \yii\easyii\helpers\Multisite::changeDbConfig($this->domain, true);
        }

        return \yii\easyii\helpers\Multisite::changeDbConfig($this->domain, false);
    }

    public static function find($id = null)
    {
        if (!empty($id)) {
            $domains = \yii\easyii\helpers\Multisite::getDomains();

            $key = array_search($id, array_column($domains, 'crc'));

            if (isset($domains[$key]) && !empty($domains[$key])) {
                $model = new static();
                $model->setAttributes($domains[$key]);

                return $model;
            }

            return null;
        }

        return \yii\easyii\helpers\Multisite::getDomains();
    }

    public function delete()
    {
        return \yii\easyii\helpers\Multisite::deleteDomain($this->domain);
    }
}