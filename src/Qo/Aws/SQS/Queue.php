<?php

/**
 * SQSのキューを管理するクラス
 *
 * @package Qo
 * @subpackage Aws
 */
namespace Qo\Aws\SQS;

use Qo\Error\Exception\QoException;

class Queue
{

    /**
     * 使用するキュー名称
     *
     * @var array
     */
    const NAMES = [
        'GEMINI_QUEUE',
        'GEMINI_PUBLISH_VER2'
    ];


    /**
     * 基本のキューURL
     *
     * @var string
     */
    private static $base_url = 'https://sqs.ap-northeast-1.amazonaws.com/637549107398';


    /**
     * @return void
     */
    private function __construct ()
    {
    }


    /**
     * @return void
     */
    private function __clone ()
    {
    }


    /**
     * 指定キューのQueueUrlを取得する
     *
     * @param  string $q_name
     * @return void
     */
    public static function getUrl ($q_name)
    {
        if (! in_array($q_name, self::NAMES)) {
            throw new QoException('存在しないキュー名称です');
        }

        return self::$base_url.'/'.$q_name;
    }

}
