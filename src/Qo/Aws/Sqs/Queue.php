<?php

/**
 * SQSのキューを管理するクラス
 *
 * @package Qo
 * @subpackage Aws
 **/
namespace Qo\Aws\Sqs;

use Qo\Error\Exception\QoException;

class Queue
{

    /**
     * 使用出来るキュー名称
     *
     * @var array
     **/
    public static $names = [
        'GEMINI_QUEUE',
        'GEMINI_PUBLISH_VER2'
    ];


    /**
     * 基本のキューURL
     *
     * @var string
     **/
    private static $base_url = 'https://sqs.ap-northeast-1.amazonaws.com/637549107398';


    /**
     * @return void
     **/
    private function __construct ()
    {
    }


    /**
     * @return void
     **/
    private function __clone ()
    {
    }


    /**
     * 指定キューのQueueUrlを取得する
     *
     * @param  string $q_name
     * @return void
     **/
    public static function getUrl ($q_name)
    {
        if (! in_array($q_name, self::$names)) {
            throw new QoException('存在しないキュー名称です');
        }

        return self::$base_url.'/'.$q_name;
    }

}
