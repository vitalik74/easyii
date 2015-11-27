<?php

namespace yii\easyii\modules\multisite\api;

class MultisiteObject extends \yii\easyii\components\ApiObject
{
    public function getDomain()
    {
        return $this->model->domain;
    }
}