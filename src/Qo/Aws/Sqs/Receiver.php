<?php

/**
 * 指定したSQSのキューメッセージを受け取るクラス
 *
 * @package Qo
 * @subpackage Aws
 */
namespace Qo\Aws\Sqs;

use Qo\Error\Exception\QoException;

class Receiver
{

    /**
     * @var SqsClient
     **/
    private $sqs_client;


    /**
     * @var string
     **/
    private $queue_url;


    /**
     * @param  SqsCelint $sqs_client
     * @return void
     **/
    public function setSqsClient ($sqs_client)
    {
        $this->sqs_client = $sqs_client;
    }


    /**
     * @param  string $queue_url
     * @return void
     **/
    public function setQueueUrl ($queue_url)
    {
        $this->queue_url = $queue_url;
    }


    /**
     * SQSをデキューする処理
     *
     * @return object or null
     **/
    public function execute ()
    {
        $this->_validateParameters();
        return $this->_receiveMessage();
    }


    /**
     * パラメータが正しく設定されているかを判別する
     *
     * @return void
     **/
    private function _validateParameters ()
    {
        if (is_null($this->sqs_client)) {
            throw new QoException('SQSクライアントクラスが指定されていません');
        }

        if (is_null($this->queue_url)) {
            throw new QoException('キューのURLが指定されていません');
        }
    }


    /**
     * SQSメッセージを取得する
     *
     * @return object or null
     **/
    private function _receiveMessage ()
    {
        $response = $this->sqs_client->receiveMessage([
            'QueueUrl' => $this->queue_url,
            'VisibilityTimeout' => 30,
            'WaitTimeSeconds' => 3
        ]);

        $message = $response->getPath('Messages/*');
        if (! is_null($message)) $message = json_decode($message['Body']);

        return $message;
    }
}
