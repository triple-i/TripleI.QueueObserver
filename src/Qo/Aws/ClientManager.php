<?php

/**
 * AwsSDK の Client クラスを管理するクラス
 *
 * @package Qo
 * @subpackage Aws
 */
namespace Qo\Aws;

use Aws\Sqs\SqsClient;

use Qo\Error\Exception\QoException;

class ClientManager
{

    /**
     * @return void
     */
    private function __construct ()
    {
    }


    /**
     * Awsクライアントをインスタンス化するための環境変数を取得する
     *
     * @param  array $config  基本設定を上書きするための配列
     * @return array
     **/
    public static function getConfig ($config = [])
    {
        $access_key = getenv('AWS_ACCESS_KEY_ID');
        if ($access_key === false || $access_key === '') {
            throw new QoException('環境変数AWS_ACCESS_KEY_IDが指定されていません');
        }

        $secret_key = getenv('AWS_SECRET_ACCESS_KEY');
        if ($secret_key === false || $secret_key === '') {
            throw new QoException('環境変数AWS_SECRET_ACCESS_KEYが指定されていません');
        }

        $region = getenv('AWS_DEFAULT_REGION');
        if ($region === false || $region === '') {
            throw new QoException('環境変数AWS_DEFAULT_REGIONが指定されていません');
        }

        return array_merge([
            'key' => $access_key,
            'secret' => $secret_key,
            'region' => $region
        ], $config);
    }


    /**
     * @param  array $config
     * @return SqsClient
     */
    public static function getSqsClient ($config = [])
    {
        $config = self::getConfig($config);
        return SqsClient::factory($config);
    }
}

