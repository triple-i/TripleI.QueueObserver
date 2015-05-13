<?php


namespace Qo\Aws\Sqs;

use Aws\Sqs\SqsClient;
use Qo\Error\Exception\QoException;

class Sweeper
{

    /**
     * @var object
     **/
    private $msg;


    /**
     * @var string
     **/
    private $queue_url;


    /**
     * @var SqsClient
     **/
    private $sqs_client;


    /**
     * @param  object $msg
     * @return void
     **/
    public function setMessage ($msg)
    {
        $this->msg = $msg;
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
     * @param  SqsClient $sqs_client
     * @return void
     **/
    public function setSqsClient (SqsClient $sqs_client)
    {
        $this->sqs_client = $sqs_client;
    }


    /**
     * @return boolean
     **/
    public function execute ()
    {
        $this->_validateParameters();

        $this->sqs_client->deleteMessage([
            'QueueUrl'      => $this->queue_url,
            'ReceiptHandle' => $this->msg->receipt_handle
        ]);

        return true;
    }


    /**
     * @return void
     **/
    private function _validateParameters ()
    {
        if (is_null($this->msg)) {
            throw new QoException('キューメッセージを指定してください');
        }

        if (is_null($this->queue_url)) {
            throw new QoException('キューのURLが指定されていません');
        }

        if (is_null($this->sqs_client)) {
            throw new QoException('SqsClientクラスが指定されていません');
        }
    }

}

