<?php
namespace yii\easyii\modules\file\api;

use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii\modules\file\models\File as FileModel;
use yii\widgets\LinkPager;

class File extends \yii\easyii\components\API
{
    private $_adp;
    private $_last;
    private $_items;
    private $_files;

    public function api_items($options = [])
    {
        if(!$this->_items){
            $this->_items = [];

            $this->_adp = new ActiveDataProvider([
                'query' => FileModel::find()->with('seo')->orderBy('time DESC'),
                'pagination' => $options
            ]);

            foreach($this->_adp->models as $model){
                $this->_items[] = new FileObject($model);
            }
        }
        return $this->_items;
    }

    public function api_get($id_slug)
    {
        if(!isset($this->_files[$id_slug])) {
            $this->_files[$id_slug] = $this->findFiles($id_slug);
        }
        return $this->_files[$id_slug];
    }

    public function api_last($limit = 1)
    {
        if($limit === 1 && $this->_last){
            return $this->_last;
        }

        $result = [];
        foreach(FileModel::find()->with('seo')->sort()->limit($limit)->all() as $item){
            $result[] = new FileObject($item);
        }

        if($limit > 1){
            return $result;
        } else {
            $this->_last = $result[0];
            return $this->_last;
        }
    }

    public function api_pagination()
    {
        return $this->_adp ? $this->_adp->pagination : null;
    }

    public function api_pages()
    {
        return $this->_adp ? LinkPager::widget(['pagination' => $this->_adp->pagination]) : '';
    }

    private function findFiles($id_slug)
    {
        $files = FileModel::find()->where(['or', 'file_id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id_slug])->one();

        return $files ? new FileObject($files) : null;
    }
}