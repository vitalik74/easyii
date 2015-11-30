<?php

namespace yii\easyii\modules\multisite\models;


use Yii;
use yii\db\Connection;
use yii\db\mssql\PDO;
use yii\db\Query;
use yii\easyii\components\Model;
use yii\easyii\helpers\Multisite as MultisiteHelper;

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
            $container = new \yii\di\Container;

            $config = require(MultisiteHelper::getDbConfig(Yii::getAlias('@app/config/'), $this->domain));
            $container->set('db', $config);
            $configCopy = require(MultisiteHelper::getDbConfig(Yii::getAlias('@app/config/'), $this->domainCopy));
            $container->set('dbCopy', $configCopy);

            /** @var Connection $db */
            $db = $container->get('db');
            /** @var Connection $dbCopy */
            $dbCopy = $container->get('dbCopy');

            $transaction = $db->beginTransaction();

            try {
                // rename tables if database not empty
                $tables = $db->createCommand('SHOW TABLES')->queryAll(PDO::FETCH_COLUMN);
                $key = rand(1, 100000);

                foreach ($tables as $table) {
                    $db->createCommand('RENAME TABLE ' . $table . ' TO ' . $key . '_' . $table)->query();
                }

                $tables = $dbCopy->createCommand('SHOW TABLES')->queryAll(PDO::FETCH_COLUMN);

                foreach ($tables as $table) {
                    $sql = 'CREATE TABLE ' . $this->getDatabase($config['dsn']) . '.' . $table . ' LIKE  ' . $this->getDatabase($configCopy['dsn']) . '.' . $table;
                    $dbCopy->createCommand($sql)->query();

                    $sql = 'INSERT INTO ' . $this->getDatabase($config['dsn']) . '.' . $table . ' SELECT * FROM ' . $this->getDatabase($configCopy['dsn']) . '.' . $table;
                    $dbCopy->createCommand($sql)->query();
                }

                $transaction->commit();

                return true;
            }  catch(\Exception $e) {

                $transaction->rollBack();

                throw $e;
            }
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

    protected function getDatabase($dsn)
    {
        preg_match('/dbname=(\w*)/', $dsn, $matches);

        return isset($matches[1]) ? $matches[1] : '';
    }
}