<?php
namespace yii\easyii\modules\multisite\api;

use Yii;
use yii\easyii\helpers\Data;
use yii\easyii\modules\multisite\models\Multisite as MultisiteModel;

/**
 * Multisite module API
 * @package yii\easyii\modules\multisite\api
 *
 * @method static array items() list of all Sites as MultisiteObject objects
 */

class Multisite extends \yii\easyii\components\API
{
    public function api_items()
    {
        return Data::cache(MultisiteModel::CACHE_KEY, 3600, function(){
            $items = [];
            foreach(MultisiteModel::find() as $item){
                $items[] = new MultisiteObject($item);
            }
            return $items;
        });
    }
}