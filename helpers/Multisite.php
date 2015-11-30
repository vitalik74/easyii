<?php

namespace yii\easyii\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\easyii\modules\multisite\models\Multisite as MultisiteModel;

class Multisite
{
    public static $file = 'db.php';
    public static $folder = 'sites';
    public static $dir = 'db.php';

    public static function getDbConfig($path)
    {
        $domainDbConfig = $path . DIRECTORY_SEPARATOR . static::$folder . DIRECTORY_SEPARATOR . static::getDomain() . DIRECTORY_SEPARATOR . static::$file;

        if (is_file($domainDbConfig) && file_exists($domainDbConfig)) {
            return $domainDbConfig;
        }

        return $path . DIRECTORY_SEPARATOR . static::$file;
    }

    public static function getDomains()
    {
        $dir = static::getDir();
        $files = [
            [
                'status' => MultisiteModel::STATUS_ON,
                'domain' => MultisiteModel::DEFAULT_DOMAIN,
                'crc' => crc32(MultisiteModel::DEFAULT_DOMAIN)
            ]
        ];

        if (is_dir($dir)) {
            $tmp = FileHelper::findFiles($dir);
            $tmp = array_map(function ($v) use ($dir) {
                $domain = str_replace([$dir, static::$file, DIRECTORY_SEPARATOR, MultisiteModel::STATUS_OFF], '', $v);

                return [
                    'status' => strpos($v, MultisiteModel::STATUS_OFF) !== false ? MultisiteModel::STATUS_OFF : MultisiteModel::STATUS_ON,
                    'domain' => $domain,
                    'crc' => crc32($domain)
                ];
            }, $tmp);
            $files = ArrayHelper::merge($files, $tmp);
        }

        return $files;
    }

    public static function getDomain()
    {
        $domainFromSession = static::getDomainFromSession();

        return $domainFromSession ?: $_SERVER['HTTP_HOST'];
    }

    public static function getDomainFromSession()
    {
        if(!isset($_SESSION)){
            session_start();
        }

        if (isset($_SESSION[MultisiteModel::SESSION_DOMAIN_KEY]) && !empty($_SESSION[MultisiteModel::SESSION_DOMAIN_KEY])) {
            return $_SESSION[MultisiteModel::SESSION_DOMAIN_KEY];
        }

        return null;
    }

    public static function deleteDomain($domain)
    {
        $dir = static::getDir() . DIRECTORY_SEPARATOR . $domain;

        if (is_dir($dir)) {
            FileHelper::removeDirectory($dir);
        }

        return false;
    }

    public static function changeDbConfig($domain, $unavailable)
    {
        $file = static::$file;
        $newFile = MultisiteModel::STATUS_OFF . $file;

        if (!$unavailable) {
            $file = MultisiteModel::STATUS_OFF . static::$file;
            $newFile = static::$file;
        }

        return static::rename($domain, $file, $newFile);
    }

    protected static function rename($domain, $file, $newFile)
    {
        $filename = static::getDir() . DIRECTORY_SEPARATOR . $domain . DIRECTORY_SEPARATOR . $file;

        if (is_file($filename) && file_exists($filename)) {
            return rename($filename, str_replace($file, $newFile, $filename));
        }

        return false;
    }

    protected static function getDir()
    {
        return Yii::getAlias('@app/config/' . static::$folder);
    }
}