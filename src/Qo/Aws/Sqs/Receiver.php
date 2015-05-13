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
     * @var string
     **/
    private $queue_url;


    /**
     * @var SqsClient
     **/
    private $sqs_client;


    /**
     * @param  string $queue_url
     * @return void
     **/
    public function setQueueUrl ($queue_url)
    {
        $this->queue_url = $queue_url;
    }


    /**
     * @param  SqsCelint $sqs_client
     * @return void
     **/
    public function setSqsClient ($sqs_client)
    {
        $this->sqs_client = $sqs_client;
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
        if (is_null($this->queue_url)) {
            throw new QoException('キューのURLが指定されていません');
        }

        if (is_null($this->sqs_client)) {
            throw new QoException('SqsClientクラスが指定されていません');
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
            'WaitTimeSeconds' => 10
        ]);

        $message = $response->getPath('Messages/*');
        if (is_null($message)) return null;

        $message_body = json_decode($message['Body']);

        // メッセージ削除時に必要な受信ハンドル
        $message_body->receipt_handle = $message['ReceiptHandle'];

        return $message_body;
    }
}
