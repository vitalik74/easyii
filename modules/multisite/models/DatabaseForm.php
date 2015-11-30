<?php

namespace yii\easyii\modules\multisite\models;


use Yii;
use yii\easyii\components\Model;

class DatabaseForm extends Model
{
    public $domain;
    public $domainCopy;

    public function rules()
    {
        return [
            [['domain', 'domainCopy'], 'required'],
            [['domain', 'domainCopy'], 'string'],
            ['domain', 'compare', 'compareAttribute' => 'domainCopy', 'operator' => '!=']
        ];
    }

    public function save()
    {
        if ($this->validate()) {

        }

        return false;
    }

    public function attributeLabels()
    {
        return [
            'domainCopy' => Yii::t('easyii/multisite', 'Copy structure from site'),
            'domain' => Yii::t('easyii/multisite', 'Domain')
        ];
    }
}